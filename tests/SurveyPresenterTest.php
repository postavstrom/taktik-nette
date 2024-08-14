<?php

use Tester\Assert;
use Nette\Application\UI\Form;
use Mockery as m;

require __DIR__ . '/bootstrap.php';

final class SurveyPresenterTest extends \Tester\TestCase
{
    private $presenter;
    private $database;

    protected function setUp(): void
    {
        $this->database = m::mock(Nette\Database\Explorer::class);
        $this->presenter = new \App\UI\Survey\SurveyPresenter($this->database);
        $this->presenter->autoCanonicalize = false;
    }

    public function testCreateComponentSurveyForm(): void
    {
        $form = $this->presenter->getComponent('surveyForm');

        $post = [
            'name' => 'John Doe',
            'comments' => 'Test comment',
            'agreement' => true,
            'interests' => ['Sports', 'Music'],
        ];

        $form->setValues($post, true);

        $form->onSuccess[] = function (Form $form, $values) {
            Assert::equal('John Doe', $values->name);
            Assert::equal('Test comment', $values->comments);
            Assert::true($values->agreement);
            Assert::equal(['Sports', 'Music'], $values->interests);
        };

        $form->fireEvents();

        Assert::true(true);
    }

    protected function tearDown(): void
    {
        m::close();
    }
}

(new SurveyPresenterTest)->run();
