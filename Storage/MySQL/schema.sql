
/* Categories */

DROP TABLE IF EXISTS `bono_module_tour_category`;

CREATE TABLE `bono_module_tour_category` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Sorting order',
    `seo` BOOLEAN NOT NULL COMMENT 'Whether SEO is enabled'
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
    `seo` BOOLEAN NOT NULL COMMENT 'Whether SEO is enabled'
);

DROP TABLE IF EXISTS `bono_module_tour_tours_translation`;

CREATE TABLE `bono_module_tour_tours_translation` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL COMMENT 'Language identificator of this page',
    `web_page_id` INT NOT NULL COMMENT 'Attached web-page ID',
    `name` varchar(255) NOT NULL COMMENT 'Tour name',
    `description` TEXT NOT NULL COMMENT 'Tour description',

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
