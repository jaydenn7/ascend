# Ascend Technical Test

## Installation

- Clone the repository
- Run `composer install`
- Run `cp .env.example .env`
- Run `php artisan key:generate`
- Configure your database
- Run `php artisan migrate:fresh --seed`
- Run `npm install`
- Compile assets: run `npm run dev`
- Login with the following credentials:
  - Email: `test.user@test.com`
  - Password: `secret1234`

## Introduction

You are tasked with working on a Laravel/Vue/Inertia/Tailwind project, which is a basic library system allowing users to
log in and request to borrow books. The system currently has some bugs that need fixing and requires the implementation
of new features. Additionally, there's a requirement to introduce self-service functionality using scanners for book 
returns, so that borrows do not need to be manually entered into the database by administrators.

Create a new branch for your work, and commit your changes to that branch. Once you're done, please create a pull
request. We will review your pull request and provide feedback.

Please do not spend longer than 4 hours on this task. If you are unable to complete a task within the time frame,
don't worry about it.

As you complete a task, please check it off in the task list below. Feel free to complete the tasks in any order you
prefer.

### Part One: Bug Fixes

1. Performance Issue in Loading Book Copies:
   - **Bug Description:** Due to the large quantity of books in the system, users are experiencing difficulty loading the list of book copies available to them.
   - **Tasks:**
     - [ ] Identify and resolve the performance bottleneck to ensure efficient loading of book copies for users.

2. Displaying Unavailable Book Copies
   - **Bug Description:** Users are seeing book copies in their library that are not available.
   - **Tasks:** 
     - [ ] Implement logic to filter out book copies that are not available to the user, ensuring users only see copies available in their library.

3. Simultaneous Book Requests
   - **Bug Description:** Staff have complained that 2 users have been able to request to borrow a book at the same time
   - **Tasks:**
     - [ ] Investigate and implement a fix on why book copies might have multiple borrow requests.

### Part Two: Self-Service Book Returns

1. Automated Book Returns with Scanners 
   - **Feature Description:** Currently, book borrows/returns are handled manually by administrators. The libraries have invested in scanners to introduce a self-scan service for its library users. Scanners are capable of making API requests to the library system. Design a RESTful API that can handle book borrows/returns from scanners. NB: borrows are different from borrow requests - a borrow request is essentially a book that is reserved in the library, but a borrow is when the book is taken from the library therefore you will need to think about how you would record borrows/returns in the database.
   - **Tasks:**
     - [ ] Implement a RESTful API endpoint to handle book borrows from scanners.
     - [ ] Implement a RESTful API endpoint to handle book returns from scanners.

### Part Three: New Features Implementation

1. Displaying Requested Books in User Account
   - **Feature Description:** Users need visibility into the books they have requested to borrow/are currently borrowing. This can under the `book-copies.index` route for ease.
   - **Tasks:**
     - [ ] Implement functionality to display books that they have requested to borrow.
     - [ ] Implement functionality to display books that they are currently borrowing.

2. Admin User Functionality
   - **Feature Description:** Implement an admin area for library staff to manage book copies/their respective borrows.
   - **Tasks:**
     - [ ] Implement a basic admin area for staff login (all we need here is one index page/dashboard).
     - [ ] Create functionality to list users whose books are overdue.
     - [ ] Enable sorting of users by the number of books they have borrowed.
     - [ ] Enable sorting of users by the number of books they have overdue. 
     - [ ] Implement a filter to display users with borrowed books, sorted by overdue books first, and non-overdue borrows second. 
     - [ ] Develop functionality for admin users to email users (individually and in bulk) with overdue books to remind them to return their books.
     - [ ] Implement ability for super admin users to restrict other admin users from sending emails to library users

## Unit Testing

- Write unit tests where necessary to ensure the implemented features and bug fixes are working as expected.
