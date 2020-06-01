<?php
declare(strict_types=1);

namespace App\Constant;


/**
 * Class UserEvents
 * @package App\Constant
 */
class UserEvents
{
    /**
     * The CHANGE_PASSWORD_INITIALIZE event occurs when the change password process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
public const CHANGE_PASSWORD_INITIALIZE = 'change_password.edit.initialize';

    /**
     * The CHANGE_PASSWORD_SUCCESS event occurs when the change password form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
public const CHANGE_PASSWORD_SUCCESS = 'change_password.edit.success';

    /**
     * The CHANGE_PASSWORD_COMPLETED event occurs after saving the user in the change password process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
     */
public const CHANGE_PASSWORD_COMPLETED = 'change_password.edit.completed';

    /**
     * The GROUP_CREATE_INITIALIZE event occurs when the group creation process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("FOS\UserBundle\Event\GroupEvent")
     */
    public  const GROUP_CREATE_INITIALIZE = 'group.create.initialize';

    /**
     * The GROUP_CREATE_SUCCESS event occurs when the group creation form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
public const GROUP_CREATE_SUCCESS = 'group.create.success';

    /**
     * The GROUP_CREATE_COMPLETED event occurs after saving the group in the group creation process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterGroupResponseEvent")
     */
public const GROUP_CREATE_COMPLETED = 'group.create.completed';

    /**
     * The GROUP_DELETE_COMPLETED event occurs after deleting the group.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterGroupResponseEvent")
     */
public const GROUP_DELETE_COMPLETED = 'group.delete.completed';

    /**
     * The GROUP_EDIT_INITIALIZE event occurs when the group editing process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("FOS\UserBundle\Event\GetResponseGroupEvent")
     */
public const GROUP_EDIT_INITIALIZE = 'group.edit.initialize';

    /**
     * The GROUP_EDIT_SUCCESS event occurs when the group edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
public const GROUP_EDIT_SUCCESS = 'group.edit.success';

    /**
     * The GROUP_EDIT_COMPLETED event occurs after saving the group in the group edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterGroupResponseEvent")
     */
    public  const GROUP_EDIT_COMPLETED = 'group.edit.completed';

    /**
     * The PROFILE_EDIT_INITIALIZE event occurs when the profile editing process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
public const PROFILE_EDIT_INITIALIZE = 'profile.edit.initialize';

    /**
     * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
public const PROFILE_EDIT_SUCCESS = 'profile.edit.success';

    /**
     * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
     */
    public const PROFILE_EDIT_COMPLETED = 'profile.edit.completed';

    /**
     * The REGISTRATION_INITIALIZE event occurs when the registration process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const REGISTRATION_INITIALIZE = 'registration.initialize';

    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
    public const REGISTRATION_SUCCESS = 'registration.success';

    /**
     * The REGISTRATION_FAILURE event occurs when the registration form is not valid.
     *
     * This event allows you to set the response instead of using the default one.
     * The event listener method receives a FOS\UserBundle\Event\FormEvent instance.
     *
     * @Event("FOS\UserBundle\Event\FormEvent")
     */
    public const REGISTRATION_FAILURE = 'registration.failure';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
     */
    public const REGISTRATION_COMPLETED = 'registration.completed';

    /**
     * The REGISTRATION_CONFIRM event occurs just before confirming the account.
     *
     * This event allows you to access the user which will be confirmed.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
    public const REGISTRATION_CONFIRM = 'registration.confirm';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
     */
    public const REGISTRATION_CONFIRMED = 'registration.confirmed';

    /**
     * The RESETTING_RESET_REQUEST event occurs when a user requests a password reset of the account.
     *
     * This event allows you to check if a user is locked out before requesting a password.
     * The event listener method receives a FOS\UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
    public const RESETTING_RESET_REQUEST = 'resetting.reset.request';

    /**
     * The RESETTING_RESET_INITIALIZE event occurs when the resetting process is initialized.
     *
     * This event allows you to set the response to bypass the processing.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
    public const RESETTING_RESET_INITIALIZE = 'resetting.reset.initialize';

    /**
     * The RESETTING_RESET_SUCCESS event occurs when the resetting form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("FOS\UserBundle\Event\FormEvent ")
     */
    public const RESETTING_RESET_SUCCESS = 'resetting.reset.success';

    /**
     * The RESETTING_RESET_COMPLETED event occurs after saving the user in the resetting process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
     */
    public const RESETTING_RESET_COMPLETED = 'resetting.reset.completed';

    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const SECURITY_IMPLICIT_LOGIN = 'security.implicit_login';

    /**
     * The RESETTING_SEND_EMAIL_INITIALIZE event occurs when the send email process is initialized.
     *
     * This event allows you to set the response to bypass the email confirmation processing.
     * The event listener method receives a FOS\UserBundle\Event\GetResponseNullableUserEvent instance.
     *
     * @Event("FOS\UserBundle\Event\GetResponseNullableUserEvent")
     */
    public const RESETTING_SEND_EMAIL_INITIALIZE = 'resetting.send_email.initialize';

    /**
     * The RESETTING_SEND_EMAIL_CONFIRM event occurs when all prerequisites to send email are
     * confirmed and before the mail is sent.
     *
     * This event allows you to set the response to bypass the email sending.
     * The event listener method receives a FOS\UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
    public const RESETTING_SEND_EMAIL_CONFIRM = 'resetting.send_email.confirm';

    /**
     * The RESETTING_SEND_EMAIL_COMPLETED event occurs after the email is sent.
     *
     * This event allows you to set the response to bypass the the redirection after the email is sent.
     * The event listener method receives a FOS\UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("FOS\UserBundle\Event\GetResponseUserEvent")
     */
    public const RESETTING_SEND_EMAIL_COMPLETED = 'resetting.send_email.completed';

    /**
     * The USER_CREATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the creation.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_CREATED = 'user.created';

    /**
     * The USER_PASSWORD_CHANGED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the password change.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_PASSWORD_CHANGED = 'user.password_changed';

    /**
     * The USER_ACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the activated user and to add some behaviour after the activation.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_ACTIVATED = 'user.activated';

    /**
     * The USER_DEACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the deactivated user and to add some behaviour after the deactivation.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_DEACTIVATED = 'user.deactivated';

    /**
     * The USER_PROMOTED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the promoted user and to add some behaviour after the promotion.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_PROMOTED = 'user.promoted';

    /**
     * The USER_DEMOTED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the demoted user and to add some behaviour after the demotion.
     *
     * @Event("FOS\UserBundle\Event\UserEvent")
     */
    public const USER_DEMOTED = 'user.demoted';
}