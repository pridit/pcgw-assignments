<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Assign;
use App\Dxdiag;
use App\Applicant;
use App\Application;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class ApplicantController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'admin/applicants.twig', [
            'applicants' => Applicant::orderBy('name')->paginate($this->config->get('pagination.applicants'))
        ]);
    }

    public function destroy(Request $request, Response $response, $args)
    {
        Applicant::find($args['id'])->delete();

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function assign(Request $request, Response $response, $args)
    {
        $application = Application::find($args['id']);

        $assign = Assign::firstOrCreate([
            'assignment_id' => $application->assignment->id,
            'applicant_id' => $application->applicant_id,
            'assigned_by' => $this->session->get('user')->id
        ]);

        if($assign) {
            $this->flash->addMessage('alert', [
                'success',
                'Successful',
                sprintf(
                    "%s has been assigned to %s",
                    $application->assignment->title,
                    $application->applicant->name
                )
            ]);
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function unassign(Request $request, Response $response, $args)
    {
        $application = Application::find($args['id']);

        $assign = Assign::where('applicant_id', $application->applicant->id)
            ->where('assignment_id', $application->assignment->id)
            ->first()
            ->delete();

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function assigned(Request $request, Response $response, $args)
    {
        try {
            $applicant = Applicant::findOrFail($args['id']);

            return $this->view->render($response, 'admin/applicant/assigned.twig', compact('applicant'));
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function applied(Request $request, Response $response, $args)
    {
        try {
            $applicant = Applicant::findOrFail($args['id']);

            return $this->view->render($response, 'admin/applicant/applied.twig', compact('applicant'));
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function dxdiag(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('dxdiag', [
            'dxdiag' => Applicant::find($args['id'])->dxdiag
        ]);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function blacklist(Request $request, Response $response, $args)
    {
        try {
            Applicant::findOrFail($args['id'])->toggleBlacklist();
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
