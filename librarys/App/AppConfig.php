<?php

    namespace Librarys\App;

    use Librarys\Boot;
    use Librarys\File\FileInfo;
    use Librarys\App\Base\BaseConfigRead;

    final class AppConfig extends BaseConfigRead
    {

        public function __construct(Boot $boot, $pathConfigSystem = null)
        {
            parent::__construct($boot, $pathConfigSystem, 'manager.php');
            parent::parse(true);
        }

    }

?>