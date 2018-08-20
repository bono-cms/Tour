
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
