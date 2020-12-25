<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 1:31 PM
 */

namespace App\Service;

use App\BindingModel\QuestionBindingModel;
use App\Entity\Question;
use App\Entity\User;
use App\Utils\Page;
use App\Utils\Pageable;

interface QuestionService
{
    /**
     * @param QuestionBindingModel $bindingModel
     * @param User|null $user
     * @return Question
     */
    public function createQuestion(QuestionBindingModel $bindingModel, User $user = null) : Question;

    /**
     * @param int $id
     * @return Question|null
     */
    public function findOneById(int $id) : ?Question;

    /**
     * @param Pageable $pageable
     * @return Page
     */
    public function findAll(Pageable $pageable) : Page;

    /**
     * @return Question[]
     */
    public function all() : array ;
}