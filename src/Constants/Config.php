<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:19 PM
 */

namespace App\Constants;


class Config
{
    public const DEFAULT_TIMEZONE = "Europe/Sofia";

    public const SIMPLE_DATE_FORMAT = "d M, Y -  H:m a";

    public const COOKIE_BG_LANG = "bg";

    public const COOKIE_EN_LANG = "en";

    public const COOKIE_NEUTRAL_LANG = "neutral";

    public const COOKIE_LANG_NAME = "lang";

    public const PRODUCT_FILES_PATH = "files/" . "products/";

    public const GALLERIES_PATH = "files/galleries/";

    public const ENV_FB_APP_ID = "FB_APP_ID";

    public const ENV_MAILER_USER= "MAILER_USER";

    public const ENV_MAILER_SENDER_NAME = "MAILER_SENDER_NAME";

    public const ENV_APP_ENV = 'APP_ENV';

    public const ENV_ENABLE_ERROR_HANDLING = 'ENABLE_ERROR_HANDLING';

    public const KERNEL_PROJECT_DIR = 'kernel.project_dir';

}