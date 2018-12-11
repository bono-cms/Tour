
/* Categories */
DROP TABLE IF EXISTS `bono_module_tour_category`;
CREATE TABLE `bono_module_tour_category` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Sorting order',
    `seo` BOOLEAN NOT NULL COMMENT 'Whether SEO is enabled',
    `cover` varchar(255) NOT NULL COMMENT 'Cover file'
);


DROP TABLE IF EXISTS `bono_module_tour_category_translation`;
CREATE TABLE `bono_module_tour_category_translation` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL COMMENT 'Language identificator of this page',
    `web_page_id` INT NOT NULL COMMENT 'Attached web-page ID',
    `name` varchar(255) NOT NULL COMMENT 'Category name',
    `description` TEXT NOT NULL COMMENT 'Category description',
    
    `title` varchar(255) NOT NULL COMMENT 'Page title',
    `meta_keywords` TEXT NOT NULL COMMENT 'Keywords for search engines',
    `meta_description` TEXT NOT NULL COMMENT 'Meta description for search engines'
);

/* Tours */
DROP TABLE IF EXISTS `bono_module_tour_tours`;
CREATE TABLE `bono_module_tour_tours` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Sorting order',
    `seo` BOOLEAN NOT NULL COMMENT 'Whether SEO is enabled',
    `adults` INT NOT NULL COMMENT 'Number of adults',
    `children` INT NOT NULL COMMENT 'NUMBER of children',
    `published` BOOLEAN NOT NULL COMMENT 'Whether this tour is published or not',
    `recommended` BOOLEAN NOT NULL COMMENT 'Whether marked as recommended',
    `price` FLOAT NOT NULL COMMENT 'Price of this tour',
    `start_price` FLOAT NOT NULL COMMENT 'Starting price',
    `cover` varchar(255) NOT NULL COMMENT 'Cover file'
);

DROP TABLE IF EXISTS `bono_module_tour_tours_translation`;
CREATE TABLE `bono_module_tour_tours_translation` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL COMMENT 'Language identificator of this page',
    `web_page_id` INT NOT NULL COMMENT 'Attached web-page ID',
    `name` varchar(255) NOT NULL COMMENT 'Tour name',
    `description` TEXT NOT NULL COMMENT 'Tour description',
    `included` TEXT NOT NULL COMMENT 'What\'s included',
    `excluded` TEXT NOT NULL COMMENT 'What\'s not included',
    `title` varchar(255) NOT NULL COMMENT 'Page title',
    `meta_keywords` TEXT NOT NULL COMMENT 'Keywords for search engines',
    `meta_description` TEXT NOT NULL COMMENT 'Meta description for search engines'
);


/* Tour days */
DROP TABLE IF EXISTS `bono_module_tour_tours_days`;
CREATE TABLE `bono_module_tour_tours_days` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `tour_id` INT NOT NULL COMMENT 'Attached tour',
    `order` INT NOT NULL COMMENT 'Sorting order',
    `time` TIME NOT NULL COMMENT 'Optional time when it starts',

    FOREIGN KEY (tour_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS `bono_module_tour_tours_days_translations`;
CREATE TABLE `bono_module_tour_tours_days_translations` (
    `id` INT NOT NULL COMMENT 'Tour ID',
    `lang_id` INT NOT NULL COMMENT 'Language identificator of this page',
    `title` varchar(255) NOT NULL COMMENT 'Generic title',
    `description` TEXT NOT NULL COMMENT 'Detailed description',

    FOREIGN KEY (id) REFERENCES bono_module_tour_tours_days(id) ON DELETE CASCADE
);

/* Tour gallery */
DROP TABLE IF EXISTS `bono_module_tour_gallery`;
CREATE TABLE `bono_module_tour_gallery` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `tour_id` INT NOT NULL COMMENT 'Attached tour',
    `order` INT NOT NULL COMMENT 'Sorting order',
    `image` varchar(255) NOT NULL COMMENT 'Image file',

    FOREIGN KEY (tour_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE
);

/* Tour-category relation */
DROP TABLE IF EXISTS `bono_module_tour_category_relation`;
CREATE TABLE `bono_module_tour_category_relation` (
    `master_id` INT NOT NULL COMMENT 'Tour ID',
    `slave_id` INT NOT NULL COMMENT 'Category ID',

    FOREIGN KEY (master_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES bono_module_tour_category(id) ON DELETE CASCADE
);

/* Related tours */
DROP TABLE IF EXISTS `bono_module_tour_related_relation`;

CREATE TABLE `bono_module_tour_related_relation` (
    `master_id` INT NOT NULL COMMENT 'Main tour ID',
    `slave_id` INT NOT NULL COMMENT 'Related Tour ID',

    FOREIGN KEY (master_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE,
    FOREIGN KEY (slave_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE
);

/* Tour bookings */
DROP TABLE IF EXISTS `bono_module_tour_booking`;
CREATE TABLE `bono_module_tour_booking` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `tour_id` INT NOT NULL COMMENT 'Attached tour ID',
    `status` SMALLINT NOT NULL COMMENT 'Order status',
    `tour` varchar(255) NOT NULL COMMENT 'Tour name',
    `client` varchar(255) NOT NULL COMMENT 'Client name',
    `email` varchar(255) NOT NULL COMMENT 'Client email',
    `phone` varchar(255) NOT NULL COMMENT 'Client phone',
    `datetime` DATETIME NOT NULL COMMENT 'Unqury datetime',
    `amount` FLOAT NOT NULL COMMENT 'Price',
    `token` varchar(32) NOT NULL COMMENT 'Unique order token'
);

/* Tour reviews */
DROP TABLE IF EXISTS `bono_module_tour_reviews`;
CREATE TABLE `bono_module_tour_reviews` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `tour_id` INT NOT NULL COMMENT 'Attached tour ID',
    `datetime` DATETIME NOT NULL COMMENT 'Date and time',
    `name` varchar(255) NOT NULL COMMENT 'Author name',
    `message` TEXT NOT NULL COMMENT 'Review',
    `published` BOOLEAN NOT NULL COMMENT 'Whether this one is enabled',

    FOREIGN KEY (tour_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE
);

/* Tour dates */
DROP TABLE IF EXISTS `bono_module_tour_dates`;
CREATE TABLE `bono_module_tour_dates` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `tour_id` INT NOT NULL COMMENT 'Attached tour ID',
    `start` DATE NOT NULL COMMENT 'Start date',
    `end` DATE NOT NULL COMMENT 'End date',

    FOREIGN KEY (tour_id) REFERENCES bono_module_tour_tours(id) ON DELETE CASCADE
);
