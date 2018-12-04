Hello :)

This is simple api that uses OAuth2 for authorization.

Unauthenticated users can see all tags, articles and comments.

Authenticated user can create an article (he needs to provide access token), edit and delete his own articles.

Authenticated user can comment any article and comments can be deleted by user who created it or by user who created article.

Endpoints are:

All users:

GET /api/tags - get the list of tags<br>
GET /api/articles - get all articles paginated by default articles per page<br>
GET /api/articles?per_page=integer - integer can be between 2 and 50<br>
GET /api/articles/id - id of an article<br>
POST /api/register - must provide name, email, password<br>
POST /api/login - must provide email and password<br>

Authenticated users:<br>
headers: Authorization: Bearer acces_token<br>

POST /api/articles - creates an aricle<br>
    headers: Content-Type: multipart/form-data<br>
    body: <br>
    {<br>
        "title": "some title",<br>
        "body": "some text",<br>
        "tags[]": id of some tag,<br>
        "tags[]": id of other tag,<br>
        "image": upload an image<br>
    }<br>
    <br>
POST /api/articles/id - updates existing article by id<br>
    headers: Content-Type: multipart/form-data<br>
    body: <br>
    {<br>
        "title": "new title",<br>
        "body": "new text",<br>
        "tags[]": id of some tag,<br>
        "tags[]": id of other tag,<br>
        "image": upload an image<br>
    }<br>
<br>
POST /api/articles/delete/id - deletes this article (only owner can delete it)<br>
    <br>
GET /api/my-articles - auth user can see his own articles<br>
<br>
POST /api/comment - creates a comment<br>
    headers: Content-Type: multipart/form-data<br>
    body:<br>
    {<br>
        "article_id": existing article id,<br>
        "body": "text of your comment"<br>
    }<br>
    
<br>
POST /api/delete-comment/id - deletes a comment if user is an owner of that comment or an owner of article that was commented<br>
   
You can test it on:<br>
 
Cheers !