# Blog API Documentation

## 1. Introduction

Welcome to the Blog API. This documentation provides a detailed guide on how to interact with the API endpoints.

**Base URL:** All API URLs are relative to the following base URL:
`http://your-domain.com/api/v1`

## 2. Authentication

The API uses token-based authentication with Laravel Sanctum. To access protected routes, you must include an `Authorization` header with your API token.

**Header Format:** `Authorization: Bearer <YOUR_API_TOKEN>`

### Authentication Endpoints

- **`POST /register`**: Register a new user.
- **`POST /login`**: Log in to get an API token.
- **`POST /logout`**: Log out and invalidate the current token (Authentication required).

## 3. Response Formats

The API returns JSON responses with a consistent structure.

### 3.1. Success Response (Single Item)

```json
{
    "success": true,
    "message": "Resource retrieved successfully.",
    "data": {
        "id": 1,
        "title": "Example Title",
        "slug": "example-title"
    }
}
```

### 3.2. Success Response (Paginated List)

```json
{
    "success": true,
    "message": "Resources retrieved successfully.",
    "data": [
        {
            "id": 1,
            "title": "First Item"
        },
        {
            "id": 2,
            "title": "Second Item"
        }
    ],
    "links": {
        "first": "http://your-domain.com/api/v1/resource?page=1",
        "last": "http://your-domain.com/api/v1/resource?page=5",
        "prev": null,
        "next": "http://your-domain.com/api/v1/resource?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "path": "http://your-domain.com/api/v1/resource",
        "per_page": 10,
        "to": 10,
        "total": 50
    }
}
```

### 3.3. Success Response (Simple List for Dropdowns)

```json
{
    "success": true,
    "message": "Resource list retrieved successfully.",
    "data": [
        {
            "id": 1,
            "title": "First Item"
        },
        {
            "id": 2,
            "title": "Second Item"
        }
    ]
}
```

### 3.4. Error Response

```json
{
    "success": false,
    "message": "Something went wrong or validation failed."
}
```

## 4. Endpoint Reference

---

### 4.1. Categories

#### GET `/categories`
- **Description:** Get a paginated list of all categories.
- **Query Parameters:**
  - `per_page` (integer, optional, default: 10): Number of items per page.
  - `search` (string, optional): Search for categories by title.
  - `with` (string, optional): Eager-load relationships (e.g., `blogs`).
- **Response:** Paginated list of categories.

---

#### GET `/categories/list`
- **Description:** Get a simple list of all categories (`id` and `title`) for dropdowns.
- **Response:** Simple list of categories.

---

#### GET `/categories/{id}`
- **Description:** Get a single category by its ID.
- **Response:** A single category object.

---

#### POST `/categories`
- **Description:** Create a new category.
- **Request Body:**
  - `title` (string, required)
  - `description` (string, optional)
- **Response:** The newly created category object.

---

#### PUT `/categories/{id}`
- **Description:** Update an existing category.
- **Request Body:**
  - `title` (string, required)
  - `description` (string, optional)
- **Response:** The updated category object.

---

#### DELETE `/categories/{id}`
- **Description:** Delete a category.
- **Response:** `204 No Content` on success.

---

### 4.2. Blogs

#### GET `/blogs`
- **Description:** Get a paginated list of all blogs.
- **Query Parameters:**
  - `per_page` (integer, optional, default: 10)
  - `search` (string, optional): Search blogs by title.
  - `created_by` (integer, optional): Filter blogs by author's user ID.
  - `with` (string, optional): Eager-load relationships (e.g., `category,tags,user`).
- **Response:** Paginated list of blogs.

---

#### GET `/blogs/list`
- **Description:** Get a simple list of all blogs (`id` and `title`).
- **Response:** Simple list of blogs.

---

#### GET `/blogs/{id}`
- **Description:** Get a single blog by its ID.
- **Response:** A single blog object with `category`, `tags`, `comments`, and `user` loaded.

---

#### POST `/blogs`
- **Description:** Create a new blog post.
- **Request Body (multipart/form-data):**
  - `title` (string, required)
  - `category_id` (integer, required)
  - `photo` (file, required, image, max 2MB)
  - `description` (string, optional)
  - `tags[]` (array of integers, optional): Array of existing tag IDs.
- **Response:** The newly created blog object.

---

#### PUT `/blogs/{id}`
- **Description:** Update an existing blog post.
- **Request Body (multipart/form-data):**
  - `title` (string, required)
  - `category_id` (integer, required)
  - `photo` (file, optional, image, max 2MB)
  - `description` (string, optional)
  - `tags[]` (array of integers, optional)
- **Response:** The updated blog object.

---

#### DELETE `/blogs/{id}`
- **Description:** Delete a blog post.
- **Response:** `204 No Content` on success.

---

### 4.3. Tags

#### GET `/tags`
- **Description:** Get a paginated list of all tags.
- **Query Parameters:**
  - `per_page` (integer, optional, default: 10)
  - `search` (string, optional): Search tags by name.
  - `with` (string, optional): Eager-load relationships (e.g., `blogs`).
- **Response:** Paginated list of tags.

---

#### GET `/tags/list`
- **Description:** Get a simple list of all tags (`id` and `name`).
- **Response:** Simple list of tags.

---

### 4.4. Users

#### GET `/users`
- **Description:** Get a paginated list of all users.
- **Query Parameters:**
  - `per_page` (integer, optional, default: 10)
  - `search` (string, optional): Search users by name.
  - `with` (string, optional): Eager-load relationships (e.g., `blogs`).
- **Response:** Paginated list of users.

---

#### GET `/users/list`
- **Description:** Get a simple list of all users (`id` and `name`).
- **Response:** Simple list of users.

---

### 4.5. Comments

#### GET `/blogs/{blog_id}/comments`
- **Description:** Get a paginated list of comments for a specific blog.
- **Query Parameters:**
  - `per_page` (integer, optional, default: 10)
  - `with` (string, optional): Eager-load relationships (e.g., `user`).
- **Response:** Paginated list of comments.

---

#### POST `/blogs/{blog_id}/comments`
- **Description:** Create a new comment on a blog.
- **Request Body:**
  - `body` (string, required)
  - `parent_id` (integer, optional): The ID of the parent comment for nested replies.
- **Response:** The newly created comment object.

---

#### PUT `/comments/{id}`
- **Description:** Update a comment. (Authorization: user must own the comment).
- **Request Body:**
  - `body` (string, required)
- **Response:** The updated comment object.

---

#### DELETE `/comments/{id}`
- **Description:** Delete a comment. (Authorization: user must own the comment).
- **Response:** `204 No Content` on success.
