# Pets Keeping Website

<u>For pet keeping business to allow their users to book online</u>
<br /><br />
<h1>Requirements</h1>
<h3>development</h3>

* Apache MySQL
* PHP 7
* HTML 5

<h3>language</h3>

* English

<h3>requirements</h3>
* SVG Supported Browser
<hr />
<h1>Usage</h1>

<ol>
<h2>database</h2>
<li>Create <b>petkeepers</b> database</li>
<li>Create <b>users</b> table: id(INT4AI), first_name(VARCHAR20), last_name(VARCHAR20), email(VARCHAR60), password(VARCHAR100), phone(VARCHAR20), activated(TINYINT2), toke(VARCHAR100), created_on(DATE), admin(TINYINT4). set token default to NULL, set admin default to 0.</li>
<li>Create <b>dates</b> table: id(INT4AI), user_id(INT4), booked(DATE), sendin(DATE), pickup(DATE), duration(INT11)</li>
<li>Create <b>pets</b> table: id(INT4AI), date_id(INT4), category(VARCHAR50), petname(VARCHAR20), petage(INT11), petweight(VARCHAR20)</li>
<h2>Test</h2>
<li>Set up gmail for <a href="https://www.google.com/landing/2step/">two-step verification</a></li>
<li>Set up gmail <a href='https://support.google.com/accounts/answer/185833?hl=en'>app password</a></li>
<li>in php folder, open <b>credential.php</b>, enter your email address and the 16-digits token which is the app password of your gmail</li>
</ol>

