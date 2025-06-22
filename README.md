
Tour Module
==========

The Tour Module is an essential tool for Travel Agencies, Tour Operators, and Destination Management Companies, allowing them to manage their tour packages efficiently. This powerful and easily extendable module for Bono CMS enables seamless online tour package management and sales.

## Features

The module provides a comprehensive solution for managing and selling tours effortlessly while ensuring a seamless user experience.

- **Tour details**: Manage essential information, including descriptions, inclusions, exclusions, and guest capacity (adults and children).
- **Multi-category support**: Assign a single tour to multiple categories simultaneously.
- **Related tours**: Attach similar or complementary tours to enhance customer options.
- **Tour gallery**: Upload and showcase multiple images for each tour.
- **Itinerary Management**: Add and manage day-by-day tour details with titles and descriptions.
- **Booking system**: Manage both priced tours and those that require inquiry for pricing.
- **Hotel Integration**: Optionally attach hotels to tour packages.
- **Pricing policy**: Define pricing based on the number of participants.
- **Recommended tours**: Highlight selected tours for customers.
- **User reviews**: Allow users to leave feedback and rate tours.
- **Advanced search**: Enable visitors to search for tours based on their preferences.

## Category template
The `tour-category.phtml` file represents the tour category template. It includes the following methods:

###  Available methods
    $category->getDescription(); // Returns category description
    $category->getName(); // Returns category name
    $category->getUrl(); // Returns category URL
    $category->getImageUrl($size); // Returns image URL
    $category->getTourCount(); // Returns tour count
    $category->getStartPrice(); // Returns minimal price of a tour
    $category->getCover(); // Returns cover basename, if available

It also contains `$tours` array with tour entities.

### Example

    <h1 class="mb-2"><?= $category->getName(); ?></h1>
    
    <div class="pt-2">
        <?= $page->getDescription(); ?>
    </div>
    
    <?php if (!empty($tours)): ?>
    <div class="row">
       <?php foreach ($tours as $tour): ?>
       <div class="col-lg-4">
           <img src="<?= $tour->getImageUrl('500x500'); ?>" class="img-fluid" />
           <h4 class="my-3"><?= $tour->getName(); ?></h4>
           <div class="py-3">
               <?= $tour->getShort(); ?>
           </div>
           <p>Days: <?= $tour->getDaysCount(); ?></p>
           <a href="<?= $tour->getUrl(); ?>">View details</a>
       </div>
       <?php endforeach; ?>
    </div>
    
    <!-- Pagination widget can be included here -->

    <?php else: ?>
    <p>No available tours</p>
    <?php endif; ?>

Pagination can be included via built-in widget. Learn more [here](https://bono.software/docs/pagination).

## Tour Template

The `tour-single.phtml` file represents the tour page template. It includes the following methods:

### General Methods

    $tour->getName(); // Returns the tour name.
    $tour->getDescription(); // Returns the tour description.
    $tour->getIncluded(); // Returns a description of included features.
    $tour->getExcluded(); // Returns a description of excluded features.
    $tour->getAdults(); // Returns the number of adults.
    $tour->getChildren(); // Returns the number of children.
    $tour->getViews(); // Returns view count.

### Gallery Methods

    $tour->getGallery(); // Returns an array of gallery image paths.
    $tour->hasGalleryControls(); // Checks if there are multiple images.
    $tour->hasGallery(); // Returns true if the tour has at least one image.

**Basic example:**
*Render gallery, if available*

    <?php if ($tour->hasGallery()): ?>
    <div class="row">
    	<?php foreach ($tour->getGallery() as $src): ?>
    		<div class="col-lg-4">
	            <img class="img-fluid" src="<?= $src; ?>">
            </div>
    	</div>
    	<?php endforeach; ?>
    </div>
    <?php endif; ?>

### Pricing Methods

    $tour->getPrice(); // Returns the current price.
    $tour->hasPrice(); // Checks if a price is set.
    $tour->hasStartPrice(); // Checks if a starting price is available.
    $tour->getStartPrice(); // Returns the starting price.

**Basic example:**
*Render start price if available. Otherwise, just render the regular price, if available.*

    <div class="pt-2">
    	<?php if ($tour->hasPrice() && !$tour->hasStartPrice()): ?>
    	<p>Price: <?= $tour->getPrice(); ?></p>
    	<?php endif; ?>
    
    	<?php if ($tour->hasStartPrice()): ?>
    	<p>Starts from: <?= $tour->getStartPrice(); ?></p>
    	<?php endif; ?>
    </div>

### Itinerary Methods

    $tour->hasDays(); // Checks if the tour has itinerary days.
    $tour->getDays(); // Returns an array of day entities.
    $tour->getDaysCount(); // Returns the number of tour days.
    $tour->getNightsCount(); // Returns the number of nights.

**Basic example:**
*Render Itinerary days, if available*

    <?php if ($tour->hasDays()): ?>
    <h2>Days Itinerary </h2>
    
    <?php foreach ($tour->getDays() as $index => $day): ?>
    <div class="pb-2">
	    <h4 class="mb-2">Day <?= $index + 1; ?>: <?= $day->getTitle(); ?></h4>
	    <?= $day->getDescription(); ?>    
    </div>
    <?php endforeach; ?>
    
    <?php endif; ?>

### Date Methods

    $tour->hasDates(); // Checks if the tour has available dates.
    $tour->getDates(); // Returns an array of available dates.

**Basic example:**
*Renders tour dates, if available*

    <?php if ($tour->hasDates()): ?>
    <table class="table table-sm">
      <thead>
          <tr>
             <th>Start date</th>   
             <th>End date</th>
          </tr>
      </thead>
      <tbody>
        <?php foreach($tour->getDates() as $date): ?>   
        <tr>
           <td><?= $date->getStart(); ?></td>
           <td><?= $date->getEnd(); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

### Review Methods

    $tour->hasReviews(); // Checks if the tour has any reviews.
    $tour->getReviews(); // Returns a collection of reviews.
    $tour->getReviewCount(); // Returns the total review count.

**Basic example:**
*Render reviews, if available*

    <?php if ($tour->hasReviews()): ?>
    <h2>Reviews (<?= $tour->getReviewCount(); ?>)</h2>
    
    <div class="row">
	    <?php foreach ($tour->getReviews() as $review): ?>
	    <div class="col-lg-4">
	         <p><?= $review->getName(); ?></p>
	         <p><small><?= $review->getDatetime(); ?></small</p>
	         <p><?= $review->getMessage(); ?></p>
	    </div>
	    <?php endforeach; ?>
    </div>
    
    <?php endif; ?>
    

## URL Generation

### Categories
To generate a URL for a category by its ID (assuming the category ID is 1), use:

    <a href="<?= $cms->createUrl(1, 'Tour (Categories)'); ?>">View category</a>

### Posts
To generate a URL for a tour by its ID (assuming the post ID is 1), use:

    <a href="<?= $cms->createUrl(1, 'Tour (Tours)'); ?>">View tour</a>

### Hotels
To generate a URL for a hotel by its ID (assuming the hotel ID is 1), use:

    <a href="<?= $cms->createUrl(1, 'Tour (Hotels)'); ?>">View hotel</a>

## Global service

A globally available service object named `$tourService` provides access to tour-related data and utilities. It offers the following methods:

### Getting Categories and Their Tours

Use the method:

`$tourService->getBasic()`

This returns a list of tours grouped by category names. Each category contains an array of tour entities. Each tour entity provides methods such as `getUrl()` and `getTour()`.

Example:

    <?php foreach ($tourService->getBasic() as $categoryName => $tours): ?>
    <h4 class="mb-4"><?= $categoryName; ?></h4>
     
     <ul class="list-unstyled">   
      <?php foreach ($tours as $tour): ?>
        <li>
           <a href="<?= $tour->getUrl(); ?>"><?= $tour->getTour(); ?></a>
        </li>
      <?php endforeach; ?>
     </ul>
    <?php endforeach; ?>

### Getting Recommended Tours

Recommended tours are selected in the admin panel.
Use the method:

    $tourService->getRecommended($limit);

-   **Parameters:**
    
    -   `$limit` _(int)_ – The maximum number of recommended tours to return.
        
-   **Returns:** An array of recommended tour entities.

### Getting Destinations

Use the method:

`$tourService->getDestinations()`

-   **Returns:** An array of destinations, each containing an ID and name.

### Getting Categories

Use the method:

`$tourService->getCategories($hash)`

-   **Parameters:**
    
    -   `$hash` _(bool)_ – If `true`, returns categories as a hash map. If `false`, returns an array of category entities.
        
-   **Returns:** Tour categories in the specified format.