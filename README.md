<h1>Product Task</h1>

<h3>The Technologies use in this App:</h3>
<ul>
    <li>Laravel</li>
    <li>Vue.js</li>
    <li>axios.js</li>
    <li>jquery</li>
</ul>

<h3>Features of The App:</h3>
<ul>
    <li>View App Products in customised cards With dynamic pagination and fetching due to category</li>
    <li>Single Page for product details</li>
    <li>Protection as all connection with Database is done with laravel/passport and connection to the apis only done with the token (protected), also these are the pages(category page, add product page) and features(add, edit and deleting Products and categories) that are strict to just the admin(this done by creating autharisation by vue-router)</li>
</ul>

<h3>Pages of the App:</h3>

<h4>Product pages</h4>
<ul>
    <li>
        <h5>Home Page (shop)<h5>
        <p>Has all App products and has Categories navbar chich will enable you to view products with only specific category with just one click, has full dynamic pagination(built from scratch by me) </p>
    </li>
    <li>
        <h5>Single Product<h5>
        <p>For guests and users : View single product </p>
        <p>For admin : View single product and be able to edit on it or delete it</p>
    </li>
    <li>
        <h5>Add Product<h5>
        <p>only accessable by admin </p>
    </li>
</ul>

<h4>categories pages</h4>
<ul>
    <li>
        <h5>Main categories page ( only accessabe by admin )<h5>
        <p>View perfome all CRUD operations on categories only by admin </p>
    </li>
</ul>

<h3>Installation:</h3>
    <li>install all modules listed as dependencies in package.json (npm i)</li>
    <li>
        Admin Credential:
        <ul>
            <p>- Username: mohamed7el17@gmail.com</p>         
            <p>- Password: 123</p>         
        </ul>
    </li>