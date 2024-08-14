<?php

declare(strict_types=1);

namespace App\UI\Results;

use Nette;
use Nette\Database\Explorer;
use Nette\Utils\Paginator;
use Nette\Http\Session;

final class ResultsPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $session;

    public function __construct(Nette\Database\Explorer $database, Session $session)
    {
        $this->database = $database;
        $this->session = $session;
    }

    public function actionDefault(): void
    {
        $formData = $this->getHttpRequest()->getPost();
        $params = $this->session->getSection('resultsParams');

        if (isset($formData['action'])) {
            if ($formData['action'] === 'apply') {
                $params->name = $formData['name'] ?? null;
                $params->page = 1;
                $params->order = $formData['order'] ?? 'name';
                $params->direction = $formData['direction'] ?? 'asc';
            } elseif ($formData['action'] === 'cancel') {
                $params->remove();

                $params = $this->session->getSection('resultsParams');
                $params->page = 1;
            }

            $this->redirect('this');
        }

        if (isset($formData['page'])) {
            $params->page = (int)$formData['page'];
        }

        $page = (int)($params->page ?? 1);
        $params->page = $page;
    }

    public function renderDefault(): void
    {
        $params = $this->session->getSection('resultsParams');

        $name = $params->name ?? null;
        $page = (int)($params->page ?? 1);
        $order = $params->order ?? 'name';
        $direction = $params->direction ?? 'ASC';

        $validDirections = ['ASC', 'DESC'];
        if (!in_array($direction, $validDirections)) {
            $direction = 'ASC';
        }

        $query = $this->database->table('survey_responses');

        if ($name) {
            $query->where('name LIKE ?', '%' . $name . '%');
        }

        $query->order($order . ' ' . $direction);

        $itemsPerPage = 2;
        $paginator = new Nette\Utils\Paginator;
        $paginator->setPage($page);
        $paginator->setItemsPerPage($itemsPerPage);
        $paginator->setItemCount($query->count('*'));

        $this->template->paginator = $paginator;
        $this->template->responses = $query->limit($paginator->getLength(), $paginator->getOffset());
        $this->template->page = $page;
        $this->template->name = $name;
        $this->template->order = $order;
        $this->template->direction = $direction;
    }
}
