<div class="api_content">
<h1>Developers API - User guide</h1>

<p class="intro">
	Hello, we developed some application program interfaces that helps you while programming rich apps/services that can reads fast and in a secure manner through our database.<br />
	All our APIs output datas in <strong>JSON</strong> format (a universal format to exchange data) and all requests come with 3 defaults keys: <strong>status_code</strong> (a number that indicates the response status - according to HTTP status codes, 200 stands for OK and 204 for "no data"), <strong>base_url</strong> (contains the default base url to use on all API requests) and finally <strong>data</strong> that is the output data you have to parse.<br />
	Feel free to <a href="#" onclick="see.load_page('misc/drop');">contact us</a> if you have any questions or doubt about our APIs.
</p>

<div class="api_status">
	<div class="api_status_title">Service status: <span class="green">ONLINE</span></div>
	<p class="api_status_update">updated 5 minutes ago</p>
</div>

<br />
<div class="separator"></div>

<h2>Get published weeks details</h2>
<h3>API URL: <a target="_blank" rel="external" href="<?php echo base_url(); ?>api/json/weeks/list">api/json/weeks/list</a></h3>
<p>With this API you can get all the published week on <strong>We Love Difference</strong>. The data array contains various informations about each week (but not its photos). To get the published photos of a single week, use the <a href="#singleapi">Single week API</a> as described below.</p>
<pre>
	<code>
{
	"status_code":200,
	"base_url":"<?php echo base_url(); ?>",
	"data": [
		{
			"week":"1",
			"title":"Lorem ipsum",
			"description":"Dolor sit amet",
			"deadline":"2011-01-31",
			"status":"closed",
			"api": {
				"photos":"api/json/week/1"
			}
		},
		{
			"week":"2",
			"title":"Dolor sit amet",
			"description":"Lorem ipsum dolor",
			"deadline":"2011-01-31",
			"status":"closed",
			"api": {
				"photos":"api/json/week/2"
			}
		}
	]
}
	</code>
</pre>

<div class="separator"></div>

<h2>Get next-week details</h2>
<h3>API URL: <a target="_blank" rel="external" href="<?php echo base_url(); ?>api/json/week/next">api/json/week/next</a></h3>
<p>You can fetch the details of the next week with this simple API.</p>
<pre>
	<code>
{
	"status_code":200,
	"base_url":"<?php echo base_url(); ?>",
	"data": {
		"week":"1",
		"title":"Lorem ipsum",
		"description":"Dolor sit amet",
		"deadline":"2011-01-31",
		"status":"open"
	}
}
	</code>
</pre>

<div class="separator"></div>

<h2 id="singleapi">Get single-week details (and photos)</h2>
<h3>API URL: <a target="_blank" rel="external" href="<?php echo base_url(); ?>api/json/week/1">api/json/week/{NUM}</a></h3>
<p>As you can see, is so easy to get all the details about a single week. You will get an array called "<strong>photos</strong>" which contains all the published photo of the week. Remember to prepend the <strong>base_url</strong> before the photo src and thumb paths. Keep in mind that the <strong>width</strong> and <strong>height</strong> params refers to the thumbnail image.</p>
<pre>
	<code>
{
	"status_code":200,
	"base_url":"<?php echo base_url(); ?>"
	"data": {
		"week":"1",
		"title":"Lorem ipsum",
		"deadline":"2011-01-31",
		"status":"open",
		"description":"Dolor sit amet",
		"photos": [
			{
				"uid":"WLD00001234",
				"title":"Photo abstract",
				"thumb":"path/to/thumbnail.jpg",
				"src":"path/to/img.jpg",
				"width":"320",
				"height":"180",
				"author_name":"Superman",
				"author_www":"www.example.org",
				"likes":"1"
			},
			{
				"uid":"WLD00001235",
				"title":"Second photo abstract",
				"thumb":"path/to/thumbnail.jpg",
				"src":"path/to/second_img.jpg",
				"width":"320",
				"height":"400",
				"author_name":"Spiderman",
				"author_www":"www.example.org",
				"likes":"10"
			}
		]
	}
}
	</code>
</pre>

<div class="separator"></div>

</div>