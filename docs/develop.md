* jQuery Ajax, always use relative path, otherwise a new request will be sent. 

<a href='https://stackoverflow.com/a/506004/11645617'>`window.location.replace('');`</a>

# things to check out if session not working on server:

* first, check server `php.ini` for read and write-in access. 
 
* second, specify a file directory to store the session. before session start (Works on my computer, not working on server)

`$sessdir = dirname(dirname(__FILE__)).'/session_dir';`
`ini_set('session.save_path', $sessdir);`

* third, config Ajax for session (seems to have no effect):

`xhrFields: {withCredentials: true},`

* fourth: set to allow url include before session start (seems to have no effect):

`ini_set('allow_url_include', 'On');`

* fifth: set session and cookie param before session start (works on my local machine, doesn't work on the server):

`session_set_cookie_params(0, '/', 'domain name');`

* sixth: try both storing session into database and file directory use [this GitHub Repo][1]. (it works on my machine but not on the server);


* seventh: extends session existing time (seems to have no effect)

`ini_set('session.gcmaxlifetime', 2*60*60) // two hours`
