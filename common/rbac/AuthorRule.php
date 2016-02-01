<?php

namespace common\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params

$auth = Yii::$app->authManager;
$rule = new \common\rbac\AuthorRule;
$auth->add($rule);

$updateOwnPost = $auth->createPermission('updateOwnAuto');
$updateOwnPost->description = 'Update own auto';
$updateOwnPost->ruleName = $rule->name;
$auth->add($updateOwnPost);
 *
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']->account_id == $user : false;
    }
}

?>