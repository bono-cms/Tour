
Tour Module
==========

The Tour Module is an essential tool for Travel Agencies, Tour Operators, and Destination Management Companies, allowing them to manage their tour packages efficiently. This powerful and easily extendable module for Bono CMS enables seamless online tour package management and sales.

## Features

The module provides a comprehensive solution for managing and selling tours effortlessly while ensuring a seamless user experience.

- **Tour Details**: Manage essential information, including descriptions, inclusions, exclusions, and guest capacity (adults and children).
- **Multi-Category Support**: Assign a single tour to multiple categories simultaneously.
- **Related Tours**: Attach similar or complementary tours to enhance customer options.
- **Tour Gallery**: Upload and showcase multiple images for each tour.
- **Itinerary Management**: Add and manage day-by-day tour details with titles and descriptions.
- **Booking System**: Sell both priced and free tours.
- **Hotel Integration**: Optionally attach hotels to tour packages.
- **Pricing Policy**: Define pricing based on the number of participants.
- **Recommended Tours**: Highlight selected tours for customers.
- **User Reviews**: Allow users to leave feedback and rate tours.
- **Advanced Search**: Enable visitors to search for tours based on their preferences.

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

### Pricing Methods

    $tour->getPrice(); // Returns the current price.
    $tour->hasPrice(); // Checks if a price is set.
    $tour->hasStartPrice(); // Checks if a starting price is available.
    $tour->getStartPrice(); // Returns the starting price.

### Itinerary Methods

    $tour->hasDays(); // Checks if the tour has itinerary days.
    $tour->getDays(); // Returns an array of day entities.
    $tour->getDaysCount(); // Returns the number of tour days.
    $tour->getNightsCount(); // Returns the number of nights.

### Date Methods

    $tour->hasDates(); // Checks if the tour has available dates.
    $tour->getDates(); // Returns an array of available dates.

### Review Methods

    $tour->hasReviews(); // Checks if the tour has any reviews.
    $tour->getReviews(); // Returns a collection of reviews.
    $tour->getReviewCount(); // Returns the total review count.
