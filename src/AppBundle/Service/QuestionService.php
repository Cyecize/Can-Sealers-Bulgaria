<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 1:31 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\QuestionBindingModel;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Utils\Page;
use AppBundle\Utils\Pageable;

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