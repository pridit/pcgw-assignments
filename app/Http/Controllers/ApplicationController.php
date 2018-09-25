<?php

namespace App\Http\Controllers;

use App\Dxdiag;
use App\Applicant;
use App\Assignment;
use App\Application;
use App\UserSetting;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class ApplicationController extends BaseController
{
    public function store(Request $request, Response $response, $args)
    {
        $assignment = $request->getParam('assignment_id');

        $applicant = Applicant::firstOrCreate([
            'name' => $this->mediawiki->user()->name
        ]);

        if ($applicant->blacklisted) {
            $this->flash->addMessage('alert', ['error', 'Unsuccessful', 'Your application could not be processed at this time.']);

            return $response->withHeader('Location', $this->container->router->pathFor('home'));
        }

        if (Application::applied($applicant->id, $assignment)->first()) {
            $this->flash->addMessage('alert', ['error', 'Unsuccessful', 'You have already applied for this assignment.']);

            return $response->withHeader('Location', $this->router->pathFor('home'));
        }

        if (Assignment::expired()->find($assignment)) {
            $this->flash->addMessage('alert', ['error', 'Unsuccessful', 'This assignment has expired.']);

            return $response->withHeader('Location', $this->router->pathFor('home'));
        }

        if (!$this->flash->getMessages()) {
            if($_FILES['dxdiag']['type'] == "text/plain") {
                $hash = sha1(rand());

                move_uploaded_file($_FILES['dxdiag']['tmp_name'], "{$hash}.txt");

                $file = $this->dxdiag->parse($hash);

                Dxdiag::updateOrCreate(['applicant_id' => $applicant->id], [
                    'os' => isset($file['Operating System']) ? $file['Operating System'] : NULL,
                    'cpu' => isset($file['Processor']) ? $file['Processor'] : NULL,
                    'gpu' => isset($file['Card name']) ? $file['Card name'] : NULL,
                    'vram' => isset($file['Dedicated Memory']) ? $file['Dedicated Memory'] : NULL,
                    'ram' => isset($file['Memory']) ? $file['Memory'] : NULL,
                    'directx' => isset($file['DirectX Version']) ? $file['DirectX Version'] : NULL,
                    'resolution' => isset($file['Native Mode']) ? $file['Native Mode'] : NULL
                ]);

                unlink("{$hash}.txt");
            }

            $application = Application::create([
                'assignment_id' => $assignment,
                'applicant_id' => $applicant->id,
                'answer_aspects' => $request->getParam('answer_aspects'),
                'answer_standard' => $request->getParam('answer_standard')
            ]);

            $assignment = Assignment::where('id', $assignment)->first();

            if($this->config->get('mailer.enabled')) {
                foreach (UserSetting::forKey('new-application')->get() as $setting) {
                    $this->pheanstalk->useTube('email')->put(json_encode([
                        'to' => $setting->user->email,
                        'subject' => 'New application for ' . $assignment->title,
                        'message' => $this->view->fetch('emails/new/application.twig', ['assignment' => $assignment, 'applicant' => $application->applicant, 'application' => $application])
                    ]));
                }
            }

            $this->flash->addMessage('alert', ['success', 'Success', 'Your application has been submitted.']);
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }
}
