CREATE TABLE IF NOT EXISTS `TABLEPREFIXannouncements` (
  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `type`    VARCHAR(10)  NOT NULL,
  `title`   VARCHAR(100) NOT NULL,
  `content` VARCHAR(500) NOT NULL,
  `active`  TINYINT(1)   NOT NULL DEFAULT '1',
  `time`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =2;

INSERT INTO `TABLEPREFIXannouncements` (`id`, `type`, `title`, `content`, `active`, `time`) VALUES
  (1, 'danger', 'Test Announcement', 'Just testing a dangerous announcement', 1, '2014-06-03 02:38:27');

CREATE TABLE IF NOT EXISTS `TABLEPREFIXcomments` (
  `id`       INT(11)                 NOT NULL AUTO_INCREMENT,
  `userid`   INT(11)                 NOT NULL,
  `ref_id`   INT(11)                 NOT NULL,
  `ref_name` VARCHAR(10)
             COLLATE utf8_unicode_ci NOT NULL,
  `content`  VARCHAR(500)
             COLLATE utf8_unicode_ci NOT NULL,
  `time`     TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =135;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXcontact` (
  `contact_id` INT(11)                 NOT NULL AUTO_INCREMENT,
  `userid`     INT(11)                 NOT NULL,
  `firstname`  VARCHAR(100)
               COLLATE utf8_unicode_ci NOT NULL,
  `lastname`   VARCHAR(100)
               COLLATE utf8_unicode_ci NOT NULL,
  `email`      VARCHAR(255)
               COLLATE utf8_unicode_ci NOT NULL,
  `subject`    VARCHAR(200)
               COLLATE utf8_unicode_ci NOT NULL,
  `message`    TEXT
               COLLATE utf8_unicode_ci NOT NULL,
  `timestamp`  TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =29;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXconversations` (
  `userid`          INT(11)    NOT NULL,
  `conversation_id` INT(11)    NOT NULL,
  `status`          TINYINT(1) NOT NULL
  COMMENT '0=read,1=unread,2=inactive',
  UNIQUE KEY `userid` (`userid`, `conversation_id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXevents` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `userid`           INT(11)      NOT NULL,
  `title`            VARCHAR(150) NOT NULL,
  `description`      TEXT         NOT NULL,
  `place`            VARCHAR(500) NOT NULL,
  `start`            DATETIME     NOT NULL,
  `imageId`          INT(11)      NOT NULL,
  `type`             VARCHAR(10)  NOT NULL,
  `privacy`          INT(11)      NOT NULL,
  `guest_invitation` TINYINT(1)   NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =8;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXevents_guests` (
  `guestid` INT(11) NOT NULL AUTO_INCREMENT,
  `userid`  INT(11) NOT NULL,
  `eventid` INT(11) NOT NULL,
  `status`  INT(11) NOT NULL,
  PRIMARY KEY (`guestid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =25;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXforums` (
  `id`                INT(11)      NOT NULL AUTO_INCREMENT,
  `title`             VARCHAR(50)  NOT NULL,
  `description`       VARCHAR(100) NOT NULL,
  `board_permissions` TINYINT(1)   NOT NULL DEFAULT '0',
  `owner_id`          INT(11)               DEFAULT NULL,
  `owner_type`        VARCHAR(10)
                      CHARACTER SET utf8    DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =36;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXforums_boards` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `forum_id`    INT(11)      NOT NULL,
  `title`       VARCHAR(70)  NOT NULL,
  `description` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =15;


CREATE TABLE IF NOT EXISTS `TABLEPREFIXforums_categories` (
  `id`   INT(11)     NOT NULL AUTO_INCREMENT,
  `tag`  VARCHAR(50) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =3;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXforums_posts` (
  `id`        INT(11)       NOT NULL AUTO_INCREMENT,
  `userid`    INT(11)       NOT NULL,
  `content`   TEXT          NOT NULL,
  `thread_id` INT(11)       NOT NULL,
  `time`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =61;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXforums_threads` (
  `id`        INT(11)              NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR(100)
              CHARACTER SET latin1 NOT NULL,
  `board_id`  INT(11)              NOT NULL,
  `userid`    INT(11)              NOT NULL,
  `important` TINYINT(1)           NOT NULL DEFAULT '0',
  `category`  INT(11)              NOT NULL DEFAULT '0',
  `time`      TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =13;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXgallery_albums` (
  `id`          INT(11)                 NOT NULL AUTO_INCREMENT,
  `owner_id`    INT(11)                 NOT NULL,
  `owner_type`  VARCHAR(10)
                COLLATE utf8_unicode_ci              DEFAULT NULL,
  `type`        ENUM('profile', 'newsfeed', 'shared', 'event')
                COLLATE utf8_unicode_ci              DEFAULT NULL,
  `user_upload` TINYINT(1)              NOT NULL     DEFAULT '0'
  COMMENT 'allow shared users to upload images',
  `title`       VARCHAR(25)
                COLLATE utf8_unicode_ci NOT NULL,
  `description` VARCHAR(140)
                COLLATE utf8_unicode_ci NOT NULL,
  `privacy`     INT(11)                 NOT NULL     DEFAULT '2'
  COMMENT '0=shared,1=friends,2=public',
  `photo_count` INT(11)                 NOT NULL,
  `cover`       INT(11)                 NOT NULL,
  `time`        TIMESTAMP               NOT NULL     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =34;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXgallery_images` (
  `id`         INT(11)                 NOT NULL AUTO_INCREMENT,
  `albumid`    INT(11)                 NOT NULL,
  `caption`    VARCHAR(500)
               COLLATE utf8_unicode_ci NOT NULL,
  `owner_id`   INT(11)                 NOT NULL,
  `owner_type` VARCHAR(10)
               COLLATE utf8_unicode_ci          DEFAULT NULL,
  `filename`   VARCHAR(255)
               COLLATE utf8_unicode_ci NOT NULL,
  `time`       TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ref`        INT(11)                          DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =125;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXlikes` (
  `id`       INT(11)                 NOT NULL AUTO_INCREMENT,
  `userid`   INT(11)                 NOT NULL,
  `ref_id`   INT(11)                 NOT NULL,
  `ref_name` VARCHAR(10)
             COLLATE utf8_unicode_ci NOT NULL,
  `dislike`  TINYINT(1)              NOT NULL DEFAULT '0',
  `time`     TIMESTAMP               NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`, `ref_id`, `ref_name`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =181;


CREATE TABLE IF NOT EXISTS `TABLEPREFIXmenu` (
  `id`        INT(11)                 NOT NULL AUTO_INCREMENT,
  `pos`       INT(11)                 NOT NULL,
  `menu`      ENUM('main', 'footer')
              COLLATE utf8_unicode_ci NOT NULL,
  `type`      ENUM('module', 'link', 'page')
              COLLATE utf8_unicode_ci NOT NULL,
  `title`     VARCHAR(20)
              COLLATE utf8_unicode_ci NOT NULL,
  `content`   VARCHAR(50)
              COLLATE utf8_unicode_ci NOT NULL,
  `iconClass` VARCHAR(20)
              COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =13;

INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (1, 0, 'main', 'module', 'Newsfeed', 'newsfeed', 'rss');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (2, 1, 'main', 'module', 'Profile', 'profile', 'user');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (3, 2, 'main', 'module', 'Gallery', 'gallery', 'picture-o');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (4, 3, 'main', 'module', 'Friends', 'friends', 'users');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (5, 4, 'main', 'module', 'Conversations', 'messages', 'comments');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (6, 3, 'main', 'module', 'Memberlist', 'memberlist', 'list');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (7, 0, 'footer', 'page', 'Legal Notice', 'legalnotice', '');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (8, 1, 'footer', 'page', 'Terms & Conditions', 'terms', '');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (9, 2, 'footer', 'page', 'Privacy', 'privacy', '');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (10, 3, 'footer', 'module', 'Contact', 'contact', '');
INSERT INTO `TABLEPREFIXmenu` (`id`, `pos`, `menu`, `type`, `title`, `content`, `iconClass`) VALUES
  (11, 5, 'main', 'module', 'Forum', 'forums', 'bullhorn');

CREATE TABLE IF NOT EXISTS `TABLEPREFIXmessages` (
  `id`           INT(11)                  NOT NULL AUTO_INCREMENT,
  `sender`       INT(11)                  NOT NULL,
  `source`       ENUM('messages', 'chat') NOT NULL DEFAULT 'messages',
  `conversation` INT(11)                  NOT NULL,
  `message`      TEXT                     NOT NULL,
  `time`         TIMESTAMP                NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =361;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXmodules` (
  `id`        INT(11)                 NOT NULL AUTO_INCREMENT,
  `namespace` VARCHAR(20)
              COLLATE utf8_unicode_ci NOT NULL,
  `name`      VARCHAR(30)
              COLLATE utf8_unicode_ci NOT NULL,
  `iconClass` VARCHAR(20)
              COLLATE utf8_unicode_ci NOT NULL,
  `status`    TINYINT(1)              NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =8;

INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (1, 'profile', 'Profile', 'user', 1);
INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (2, 'friends', 'Friends', 'users', 1);
INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (3, 'newsfeed', 'Newsfeed', 'rss', 1);
INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (4, 'gallery', 'Gallery', 'picture-o', 1);
INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (5, 'messages', 'Messages', 'comments-o', 1);
INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (6, 'memberlist', 'Memberlist', 'list', 1);
INSERT INTO `TABLEPREFIXmodules` (`id`, `namespace`, `name`, `iconClass`, `status`) VALUES
  (7, 'forums', 'Forum', 'list', 1);

CREATE TABLE IF NOT EXISTS `TABLEPREFIXnotifications` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `userid`     INT(11)      NOT NULL,
  `ref_userid` INT(11)      NOT NULL,
  `target`     VARCHAR(256) NOT NULL,
  `type`       VARCHAR(50)  NOT NULL,
  `unread`     TINYINT(1)   NOT NULL DEFAULT '0',
  `time`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =163;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXnotification_settings` (
  `userid` INT(11)     NOT NULL,
  `name`   VARCHAR(15) NOT NULL,
  `value`  TINYINT(4)  NOT NULL,
  UNIQUE KEY `userid` (`userid`, `name`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXpages` (
  `id`        INT(11)                 NOT NULL AUTO_INCREMENT,
  `shortlink` VARCHAR(100)
              COLLATE utf8_unicode_ci NOT NULL,
  `title`     VARCHAR(50)
              COLLATE utf8_unicode_ci NOT NULL,
  `content`   TEXT
              COLLATE utf8_unicode_ci NOT NULL,
  `comments`  INT(11)                 NOT NULL DEFAULT '0',
  `time`      TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =12;

INSERT INTO `TABLEPREFIXpages` (`id`, `shortlink`, `title`, `content`, `comments`, `time`) VALUES
  (1, 'legalnotice', 'Imprint',
   'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   \n\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   \n\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   \n\nNam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.   \n\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis.   \n\nAt vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.   \n\nConsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus.   \n\nLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   \n\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   \n\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   \n\nNam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo',
   0, '2014-02-24 04:48:10');
INSERT INTO `TABLEPREFIXpages` (`id`, `shortlink`, `title`, `content`, `comments`, `time`) VALUES
  (2, 'privacy', 'Privacy Policy',
   'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   \r\n\r\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   \r\n\r\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   \r\n\r\nNam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.   \r\n\r\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis.   \r\n\r\nAt vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.   \r\n\r\nConsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus.   \r\n\r\nLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   \r\n\r\nDuis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   \r\n\r\nUt wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   \r\n\r\nNam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo',
   0, '2014-03-29 14:18:52');
INSERT INTO `TABLEPREFIXpages` (`id`, `shortlink`, `title`, `content`, `comments`, `time`) VALUES
  (3, 'terms', 'Terms & Conditions',
   'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.',
   1, '2014-03-29 14:18:47');

CREATE TABLE IF NOT EXISTS `TABLEPREFIXposts` (
  `id`      INT(11)                 NOT NULL AUTO_INCREMENT,
  `userid`  INT(11)                 NOT NULL,
  `wall_id` INT(11)                 NOT NULL,
  `time`    TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type`    VARCHAR(10)
            COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `privacy` INT(11)                 NOT NULL,
  `content` VARCHAR(1500)
            COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =158;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXprivacy` (
  `userid` INT(11)             NOT NULL,
  `type`   VARCHAR(20)         NOT NULL,
  `value`  ENUM('0', '1', '3') NOT NULL,
  UNIQUE KEY `userid` (`userid`, `type`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXprofile_pins` (
  `id`        INT(11)                 NOT NULL AUTO_INCREMENT,
  `userid`    INT(11)                 NOT NULL,
  `type`      VARCHAR(10)
              COLLATE utf8_unicode_ci NOT NULL,
  `column`    INT(11)                 NOT NULL,
  `row`       INT(11)                 NOT NULL,
  `title`     VARCHAR(53)
              COLLATE utf8_unicode_ci NOT NULL,
  `iconclass` VARCHAR(20)
              COLLATE utf8_unicode_ci NOT NULL,
  `content`   TEXT
              COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =27;


CREATE TABLE IF NOT EXISTS `TABLEPREFIXrelations` (
  `relation_id` INT(11) NOT NULL AUTO_INCREMENT,
  `sender`      INT(11) NOT NULL,
  `receiver`    INT(11) NOT NULL,
  `status`      INT(11) NOT NULL,
  PRIMARY KEY (`relation_id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =59;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXsettings` (
  `name`  VARCHAR(30)
          COLLATE utf8_unicode_ci NOT NULL,
  `value` TEXT
          COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.contact_mail', '');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.design', 'CunityRefreshed');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.filesdir', '9n5s66h14xo3');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.fullname', '1');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.language', 'de');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.siteurl', 'URL(in cunity_settings)');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.sitename', 'New Cunity');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`)
VALUES ('core.description', 'This is the TestCunity for the new Design <3');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('register.permissions', 'everyone');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('register.notification', '1');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('register.min_age', '13');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.mail_header', 'mail template header');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.mail_footer', 'mail template footer');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('messages.chat', '1');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.headline',
                                                            '&lt;img src=&quot;data:image/gif;base64,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&quot; style=&quot;width: 135px;&quot;&gt;gbxcv&lt;span style=&quot;font-size: 36px;&quot;&gt;hsdfghd&lt;/span&gt;fh');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.startpageheader', '');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.remoteversion', '2.0.0');
INSERT INTO `TABLEPREFIXsettings` (`name`, `value`) VALUES ('core.lastupdatecheck', '0');

CREATE TABLE IF NOT EXISTS `TABLEPREFIXusers` (
  `userid`         INT(11)                 NOT NULL AUTO_INCREMENT,
  `userhash`       VARCHAR(32)
                   COLLATE utf8_unicode_ci NOT NULL,
  `lang`           VARCHAR(2)
                   COLLATE utf8_unicode_ci          DEFAULT NULL,
  `username`       VARCHAR(20)
                   COLLATE utf8_unicode_ci NOT NULL,
  `name`           VARCHAR(255)
                   COLLATE utf8_unicode_ci NOT NULL,
  `email`          VARCHAR(100)
                   COLLATE utf8_unicode_ci NOT NULL,
  `password`       VARCHAR(200)
                   COLLATE utf8_unicode_ci NOT NULL,
  `firstname`      VARCHAR(50)
                   COLLATE utf8_unicode_ci NOT NULL,
  `lastname`       VARCHAR(50)
                   COLLATE utf8_unicode_ci NOT NULL,
  `profileImage`   INT(11)                 NOT NULL,
  `titleImage`     INT(11)                 NOT NULL,
  `groupid`        INT(1)                  NOT NULL,
  `salt`           VARCHAR(25)
                   COLLATE utf8_unicode_ci NOT NULL,
  `registered`     TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastAction`     TIMESTAMP               NULL     DEFAULT NULL,
  `onlineStatus`   TINYINT(1)              NOT NULL DEFAULT '1',
  `chat_available` TINYINT(1)              NOT NULL DEFAULT '1',
  `password_token` VARCHAR(100)
                   COLLATE utf8_unicode_ci          DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userhash` (`userhash`),
  UNIQUE KEY `username` (`username`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci
  AUTO_INCREMENT =28;

CREATE TABLE IF NOT EXISTS `TABLEPREFIXversions` (
  `timestamp` BIGINT(20) NOT NULL,
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp` (`timestamp`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1;


CREATE TABLE IF NOT EXISTS `TABLEPREFIXwalls` (
  `wall_id`    INT(11)                               NOT NULL AUTO_INCREMENT,
  `owner_id`   INT(11)                               NOT NULL,
  `owner_type` ENUM('profile', 'group', 'event', '') NOT NULL,
  PRIMARY KEY (`wall_id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =15;

CREATE TABLE IF NOT EXISTS TABLEPREFIXlog (
  id      INT(11)      NOT NULL AUTO_INCREMENT,
  level   VARCHAR(255) NOT NULL,
  message TEXT         NOT NULL,
  context TEXT         NOT NULL,
  user_id INT(11)      NOT NULL,
  PRIMARY KEY (id)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =1;


CREATE TABLE IF NOT EXISTS `TABLEPREFIXprofilefields` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `value`        VARCHAR(255) NOT NULL,
  `type_id`      INT(11) DEFAULT NULL,
  `registration` TINYINT(1)   NOT NULL,
  `required`     TINYINT(1)   NOT NULL,
  `deleteable`   TINYINT(1)   NOT NULL,
  `sorting`      INT(11)      NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =9;

INSERT INTO `TABLEPREFIXprofilefields` (`id`, `value`, `type_id`, `registration`, `required`, `deleteable`, `sorting`)
VALUES (1, 'Sex', 1, 1, 1, 1, 1);

CREATE TABLE IF NOT EXISTS `TABLEPREFIXprofilefields_types` (
  `id`    INT(11) NOT NULL AUTO_INCREMENT,
  `value` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =7;

INSERT INTO `TABLEPREFIXprofilefields_types` (`id`, `value`) VALUES (1, 'select');
INSERT INTO `TABLEPREFIXprofilefields_types` (`id`, `value`) VALUES (2, 'radio');
INSERT INTO `TABLEPREFIXprofilefields_types` (`id`, `value`) VALUES (3, 'bigtext');
INSERT INTO `TABLEPREFIXprofilefields_types` (`id`, `value`) VALUES (4, 'text');
INSERT INTO `TABLEPREFIXprofilefields_types` (`id`, `value`) VALUES (5, 'email');
INSERT INTO `TABLEPREFIXprofilefields_types` (`id`, `value`) VALUES (6, 'date');

CREATE TABLE IF NOT EXISTS `TABLEPREFIXprofilefields_users` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`         INT(11) NOT NULL,
  `profilefield_id` INT(11) NOT NULL,
  `value`           TEXT,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =13;

INSERT INTO `TABLEPREFIXprofilefields_users` (`id`, `user_id`, `profilefield_id`, `value`) VALUES (1, 28, 1, '2');
INSERT INTO `TABLEPREFIXprofilefields_users` (`id`, `user_id`, `profilefield_id`, `value`) VALUES (9, 28, 23, '8');
INSERT INTO `TABLEPREFIXprofilefields_users` (`id`, `user_id`, `profilefield_id`, `value`)
VALUES (10, 28, 24, 'asdfas');
INSERT INTO `TABLEPREFIXprofilefields_users` (`id`, `user_id`, `profilefield_id`, `value`) VALUES (11, 36, 23, '7');
INSERT INTO `TABLEPREFIXprofilefields_users` (`id`, `user_id`, `profilefield_id`, `value`) VALUES (12, 36, 1, '1');

CREATE TABLE IF NOT EXISTS `TABLEPREFIXprofilefields_values` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `value`           TEXT,
  `profilefield_id` INT(11) DEFAULT NULL,
  `sorting`         INT(11) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =latin1
  AUTO_INCREMENT =10;

INSERT INTO `TABLEPREFIXprofilefields_values` (`id`, `value`, `profilefield_id`, `sorting`) VALUES (1, 'Female', 1, 1);
INSERT INTO `TABLEPREFIXprofilefields_values` (`id`, `value`, `profilefield_id`, `sorting`) VALUES (2, 'Male', 1, 2);
