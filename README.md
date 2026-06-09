# Chosen Framework

**Laravel (PHP MVC Framework)**
Laravel is used for routing, request handling, database management through Eloquent ORM, MVC structuring, and Blade templating.

---

# MVC Organization

## 1. Model Layer – `Pet.php`

The `Pet` model represents the `pets` database table and contains the following core fields:

-   id
-   name
-   species
-   age
-   status
-   image

### Responsibilities

-   Handles all CRUD operations using Eloquent ORM
-   Provides filtering scopes:

    -   `scopeDogs()`
    -   `scopeCats()`
    -   `scopePokemon()`

-   Implements searching through `scopeSearch()`
-   Manages all database-related interactions for pet records

---

## 2. View Layer – `resources/views`

The View layer uses Blade templates to render user interfaces.

### Main Views

-   `home.blade.php` — Displays all pets
-   `dog.blade.php` — Shows dog records only
-   `cat.blade.php` — Shows cat records only
-   `pokemon.blade.php` — Shows Pokémon-type pets only
-   `add_pet.blade.php` — Form for adding a new pet
-   `update_pet.blade.php` — Form for editing an existing pet
-   `view_pets.blade.php` — Table view listing all pets

### Reusable Components

-   `layouts/app.blade.php` — Base layout for all pages
-   `partials/header.blade.php` — Navigation bar

### Assets

-   `/public/css/` — Stylesheets (home.css, pet.css, add.css, etc.)
-   `/public/uploads/` — Uploaded pet images

---

## 3. Controller Layer – `PetController.php`

This controller contains ten methods responsible for managing all application interactions.

### Core Methods

-   `index()` — Display all pets
-   `create()` — Show the form for adding a pet
-   `store()` — Save a new pet to the database
-   `edit($id)` — Show the edit form for the selected pet
-   `update($id)` — Update an existing pet
-   `destroy($id)` — Delete a pet
-   `viewAll()` — Display all pets in table format

### Filtering and Search Methods

-   `dogs()`
-   `cats()`
-   `pokemon()`
-   `search()`

### Controller Responsibilities

-   Validate incoming HTTP requests
-   Interact with the `Pet` model
-   Provide data to views for rendering
-   Handle redirection and file upload logic

---

# How Users Interact with Petify

Petify offers a structured and intuitive workflow for browsing, adding, editing, searching, and managing pets. The following sections describe each interactive process in detail.

---

## 1. Browsing Pets

### User Action

-   Opens the application at `http://localhost:8000`
-   Views all pets displayed in a responsive gallery
-   Can filter pets by species or view details of individual pets

### System Flow

1. The browser sends a GET request to `/`
2. The route points to `PetController@index`
3. The controller retrieves all pets using `Pet::all()`
4. Data is passed to `home.blade.php`
5. The view renders pet cards with images and details

### Navigation Options

-   Dogs: `/dog`
-   Cats: `/cat`
-   Pokemon: `/pokemon`
-   Clicking a pet image opens a modal with full details

---

## 2. Searching for Pets

### User Action

-   Enters a name or species into the search bar
-   Real-time suggestions are displayed

### System Flow

-   JavaScript captures user input
-   Client-side filtering presents instant results
-   Matching text is highlighted
-   Selecting a suggestion redirects the user to the appropriate species page with the specific pet emphasized

### Example

Typing “Bub” shows “Bubbles (Cat)” and selecting it redirects the user to the `/cat` page with that pet highlighted.

---

## 3. Adding a New Pet (Create)

### User Action

-   Selects “Add Pet”
-   Completes the form fields (name, species, age, image)
-   Submits the form

### System Flow

1. A POST request is sent to `/add-pet`
2. `PetController@store` validates the data:

    - Name is required
    - Species must be Dog, Cat, or Pokemon
    - Age must be a positive integer
    - Image must be a valid file type and under 2MB

3. If valid:

    - The image is stored in `/uploads/`
    - A database record is created
    - A confirmation message is displayed

4. If invalid:

    - The form re-displays with appropriate error messages

---

## 4. Editing Pet Information (Update)

### User Action

-   Selects a pet card and chooses the Edit option
-   Updates any necessary fields
-   Submits the updated information

### System Flow

1. GET request to `/edit-pet/{id}` loads the existing pet data
2. The user submits edits via POST to `/update-pet/{id}`
3. The controller validates and updates the record
4. If a new image is uploaded, the old one is deleted and replaced
5. A success message is shown and the user is redirected to the homepage

---

## 5. Adopting a Pet (Delete)

### User Action

-   Opens a pet modal
-   Selects the Adopt option and confirms the action

### System Flow

1. A GET request is sent to `/delete-pet/{id}`
2. `PetController@destroy` performs the following:

    - Finds the pet record
    - Removes the associated image from storage
    - Deletes the database entry

3. The pet is removed from the gallery and a confirmation message is displayed

---

## 6. Viewing All Pets (Table View)

### User Action

-   Clicks “View All” in the navigation bar

### Display Includes

-   ID
-   Name
-   Species
-   Age
-   Status
-   Thumbnail image
-   Edit and Delete actions

This view is primarily intended for administrative oversight or quick scanning of data.

---

## 7. Filtering by Species

### User Action

-   Selects a species filter such as Dogs, Cats, or Pokemon

### System Flow

-   The controller calls the corresponding model scope, such as:

    ```
    Pet::dogs()->get();
    ```

-   The filtered results are displayed in the gallery view
"# Pet-terest-Pet-Adaption-Web-System" 
