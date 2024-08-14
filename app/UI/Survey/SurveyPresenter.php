<?php

declare(strict_types=1);

namespace App\UI\Survey;

use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

final class SurveyPresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    protected function createComponentSurveyForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Name:')
            ->setRequired('Please enter your name.')
            ->addRule($form::MIN_LENGTH, 'Name must be at least %d characters long.', 2)
            ->addRule($form::MAX_LENGTH, 'Name must be at most %d characters long.', 50)
            ->setHtmlAttribute('class', 'mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm');

        $form->addTextArea('comments', 'Comments:')
            ->addRule($form::MAX_LENGTH, 'Comments must be at most %d characters long.', 500);

        $form->addCheckbox('agreement', 'I agree to the terms')
            ->setRequired('You must agree to the terms.');

        $form->addMultiSelect('interests', 'Interests:', [
            'Sports' => 'Sports',
            'Music' => 'Music',
            'Travel' => 'Travel',
            'Reading' => 'Reading'
        ])->setRequired('Please select at least one interest.')
            ->addRule($form::MIN_LENGTH, 'Please select at least %d interest.', 1);

        $form->addSubmit('submit', 'Submit');

        $form->onSuccess[] = [$this, 'surveyFormSucceeded'];

        return $form;
    }

    public function surveyFormSucceeded(Form $form, $values): void
    {
        $this->database->table('survey_responses')->insert([
            'name' => $values->name,
            'comments' => $values->comments,
            'agree_to_terms' => $values->agreement,
            'interests' => json_encode($values->interests),
        ]);

        $this->flashMessage('Thank you for your response!', 'success');
        $this->redirect('this');
    }
}
