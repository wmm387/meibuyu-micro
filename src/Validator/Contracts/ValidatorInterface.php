<?php

namespace Meibuyu\Micro\Validator\Contracts;

use Hyperf\Utils\MessageBag;
use Meibuyu\Micro\Exceptions\ValidatorException;

interface ValidatorInterface
{
    const RULE_CREATE = 'create';
    const RULE_UPDATE = 'update';

    /**
     * Set Id
     *
     * @param $id
     * @return ValidatorInterface
     */
    public function setId($id);

    /**
     * With
     *
     * @param array
     * @return $this
     */
    public function with(array $input);

    /**
     * Pass the data and the rules to the validator
     *
     * @param string $action
     * @return boolean
     */
    public function passes($action = null);

    /**
     * Pass the data and the rules to the validator or throws ValidatorException
     *
     * @param string $action
     * @return boolean
     * @throws ValidatorException
     */
    public function passesOrFail($action = null);

    /**
     * Errors
     *
     * @return array
     */
    public function errors();

    /**
     * Errors
     *
     * @return MessageBag
     */
    public function errorsBag();

    /**
     * Set Rules for Validation
     *
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules);

    /**
     * Get rule for validation by action ValidatorInterface::RULE_CREATE or ValidatorInterface::RULE_UPDATE
     *
     * Default rule: ValidatorInterface::RULE_CREATE
     *
     * @param $action
     * @return array
     */
    public function getRules($action = null);
}