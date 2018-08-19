
/* Categories */

DROP TABLE IF EXISTS `bono_module_tour_category`;

CREATE TABLE `bono_module_tour_category` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Sorting order'
);


DROP TABLE IF EXISTS `bono_module_tour_category_translation`;

CREATE TABLE `bono_module_tour_category_translation` (
    `id` INT NOT NULL,
    `lang_id` INT NOT NULL COMMENT 'Language identificator of this page',
    `name` varchar(255) NOT NULL COMMENT 'Category name',
    `description` TEXT NOT NULL COMMENT 'Category description'
);
