
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

## Tour Template

The `tour-single.phtml` file represents the tour page template. It includes the following methods:

### General Methods

    $tour->getName(); // Returns the tour name.
    $tour->getDescription(); // Returns the tour description.
    $tour->getIncluded(); // Returns a list of included features.
    $tour->getExcluded(); // Returns a list of excluded features.
    $tour->getAdults(); // Returns the number of adults.
    $tour->getChildren(); // Returns the number of children.

### Gallery Methods

    $tour->getGallery(); // Returns an array of gallery image paths.
    $tour->hasGalleryControls(); // Checks if there are multiple images.
    $tour->hasGallery(); // Returns true if the tour has at least one image.

**Basic example:**
*Render gallery, if available*

    <?php if ($tour->hasGallery()): ?>
    <div class="row">
    	<?php foreach ($tour->getGallery() as $image): ?>
    		<div class="col-lg-4">
	            <img class="img-fluid" src="<?= $image->getImageUrl('500x500'); ?>" />
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
