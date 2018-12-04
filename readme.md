Hello :)

This is simple api that uses OAuth2 for authorization.

Unauthenticated users can see all tags, articles and comments.

Authenticated user can create an article (he needs to provide access token), edit and delete his own articles.

Authenticated user can comment any article and comments can be deleted by user who created it or by user who created article.

Endpoints are:

All users:

GET /api/tags - get the list of tags
GET /api/articles - get all articles paginated by default articles per page
GET /api/articles?per_page=integer - integer can be between 2 and 50
GET /api/articles/id - id of an article
POST /api/register - must provide name, email, password
POST /api/login - must provide email and password

Authenticated users:
headers: Authorization: Bearer acces_token

POST /api/articles - creates an aricle
    headers: Content-Type: multipart/form-data
    body: 
    {
        "title": "some title",
        "body": "some text",
        "tags[]": id of some tag,
        "tags[]": id of other tag,
        "image": upload an image
    }
    
POST /api/articles/id - updates existing article by id
    headers: Content-Type: multipart/form-data
    body: 
    {
        "title": "new title",
        "body": "new text",
        "tags[]": id of some tag,
        "tags[]": id of other tag,
        "image": upload an image
    }

POST /api/articles/delete/id - deletes this article (only owner can delete it)
    
GET /api/my-articles - auth user can see his own articles

POST /api/comment - creates a comment
    headers: Content-Type: multipart/form-data
    body:
    {
        "article_id": existing article id,
        "body": "text of your comment"
    }
    

POST /api/delete-comment/id - deletes a comment if user is an owner of that comment or an owner of article that was commented
    
You can test it on:
 
Cheers !