# Recipe Sharing API

A RESTful API for sharing, discovering, and managing recipes. Built with Laravel, this API allows users to create accounts, publish recipes, search for dishes, rate and comment on recipes, and interact with other food enthusiasts.

## Features

- **User Management**: Sign up, log in, and manage user profiles.
- **Recipe Management**: Create, retrieve, and search recipes 
- **Interaction Features**: Rate recipes, save favorites.
- **Social Features**: Discover recipes published by other users.

## API Endpoints

### User Management

<details>
<summary>POST /api/signup => Register a new user</summary>

#### Parameters

| name |   type   |
| ---- | -------- |
| name | required |  
| email | required |
| password | required|
| password_confirmation| required|

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 422 | application/json | `{"message": "The name field is required. (and 2 more errors)","errors": { ... }}`
| 201 | application/json | `"Successfully Created User!"`
| 500 | application/json | `{"message" => "Error Creating User"}`

#### Example CURL
```bash
curl --location 'http://localhost:8000/api/signup' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "example",
    "email": "example@email.com",
    "password": "Password123!"
}'
```

</details>

---

<details>
<summary>POST /api/login => Authenticate and receive a token.</summary>

#### Parameters

| name |   type   |
| ---- | -------- |
| email | required |  
| password | required |

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 422 | application/json | `{"message": "The name field is required. (and 2 more errors)","errors": { ... }}`
| 401 | application/json | `{'message' => 'Unauthorized'}`
| 201 | application/json | `['accessToken' => ..., 'tokenType' => 'Bearer']`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "example@email.com",
    "password": "password"
}'
```

</details>

---

<details>
<summary>GET /api/logout => Logout and destroy token</summary>

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 200 | application/json | `"Successfully logged out"`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/logout'
```

</details>

---

<details>
<summary>PUT /profile => Update user profile details.</summary>

#### Parameters

| name |   type   |
| ---- | -------- |
| name | required if not email,password |  
| email | required if not name,password|
| password | required if not name,email|

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 200 | application/json | `"Successfully updated User profile!"`
| 422 | application/json |  `{"message": "The name field is required. (and 2 more errors)","errors": { ... }}`

### Example CURL
```bash
curl --location --request PUT 'http://localhost:8000/api/profile' \
--header 'Authorization: Bearer <token>'
```

</details>

---

### Recipe Management

<details>
<summary>POST /recipes => Create a new recipe.</summary>

#### Parameters

| name |   type   |
| ---- | -------- |
| title | required |  
| ingredients | required |
| instructions | required|

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 200 | application/json | `"Successfully Published Recipe!"`
| 422 | application/json |  `{"message": "The title field is required. (and 2 more errors)","errors": { ... }}`
| 500 | application/json | `{'message' => 'Error Creating Recipe'}`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/recipes' \
--header 'Authorization: Bearer <token>' \
--header 'Content-Type: application/json' \
--data '{
    "title": "my recipe",
    "instructions": "...",
    "ingredients": ["2 eggs", "3 breads"]
}'
```

</details>

---


<details>
<summary>GET /recipes/{id} => Retrieve a specific recipe by ID.</summary>

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 200 | application/json | `{"author_id": 1,"author_name":,"...",}` 
| 404 | application/json |  `{"message": "Recipe Not Found"}`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/recipes/10' 
```

</details>

---


<details>
<summary>GET /recipes/search =>	Search recipes</summary>

#### Parameters

| name |   type   |
| ---- | -------- |
| title | required if not instruction,ingredients |  
| instructions | required if not title,ingredients |
| ingredients | required if not title,instructions|

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 422 | application/json | `{"message": "The title field is required. (and 2 more errors)","errors": { ... }}`
| 200 | application/json |  `{'recipe_id' => $recipe->id,'author_id' => $recipe->user_id,'title' => $recipe->title,...}`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/recipes/search?title=<title>&instructions=...&ingredients=...' \
--header 'Authorization: Bearer <token>'
```

</details>

---

### Interaction Features

<details>
<summary>POST /recipes/{id}/rate => Rate a recipe (1-5 stars).</summary>

#### Parameters

| name |   type   |
| ---- | -------- |
| rate | required |  

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 422 | application/json | `{"message": "The rate field is required.", "errors": ...}`
| 200 | application/json |  `"Rating Saved Successfully!"`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/recipes/1/rate' \
--header 'Authorization: Bearer <token>' \
--header 'Content-Type: application/json' \
--data '{
    "rate": 2
}'
```

</details>

---

<details>
<summary>PUT /recipes/{id}/save => Save a recipe to favorites.</summary>

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 200 | application/json |  `"Successfully Saved Recipe to Favorites"`

### Example CURL
```bash
curl --location --request PUT 'http://localhost:8000/api/recipes/1/save' \
--header 'Authorization: Bearer <token>'
```

</details>

---

### Social Features

<details>
<summary>GET /users/{user_id}/recipes => Get all recipes by a specific user.</summary>

#### Responses

| http-code| content-type | response |
| -------- | ------------ | ------- |
| 200 | application/json |  `{"recipe_id": ...,"title": ...,"ingredients": ...,"instructions": ...}`

### Example CURL
```bash
curl --location 'http://localhost:8000/api/users/1/recipes'
```

</details>


