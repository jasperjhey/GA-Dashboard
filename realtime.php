<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hello Analytics Reporting API V4</title>
</head>
<body>

<button id="auth-button" hidden>Authorize</button>

<h1>Hello Analytics Reporting API V4</h1>

<textarea cols="80" rows="20" id="query-output"></textarea>

<script>

  // Replace with your client ID from the developer console.
  var CLIENT_ID = '403771359085-kqk8d8i52reqrjeoi1h3vmpuuc6818c9.apps.googleusercontent.com';

  // Replace with your view ID.
  var VIEW_ID = '123303878';

  // Set the discovery URL.
  var DISCOVERY = 'https://analyticsreporting.googleapis.com/$discovery/rest';

  // Set authorized scope.
  var SCOPES = ['https://www.googleapis.com/auth/analytics.readonly'];


  function authorize(event) {
    // Handles the authorization flow.
    // `immediate` should be false when invoked from the button click.
    var useImmdiate = event ? false : true;
    var authData = {
      client_id: CLIENT_ID,
      scope: SCOPES,
      immediate: useImmdiate
    };

    gapi.auth.authorize(authData, function(response) {
      var authButton = document.getElementById('auth-button');
      if (response.error) {
        authButton.hidden = false;
      }
      else {
        authButton.hidden = true;
        queryReports();
      }
    });
  }

  function queryReports() {
    // Load the API from the client discovery URL.
    gapi.client.load(DISCOVERY
    ).then(function() {

        // Call the Analytics Reporting API V4 batchGet method.
        gapi.client.analyticsreporting.reports.batchGet( {
          "reportRequests":[
          {
            "viewId":VIEW_ID,
            "dateRanges":[
              {
                "startDate":"7daysAgo",
                "endDate":"today"
              }],
            "metrics":[
              {
                "expression":"ga:sessions"
              }]
          }]
        } ).then(function(response) {
          var formattedJson = JSON.stringify(response.result, null, 2);
          document.getElementById('query-output').value = formattedJson;
        })
        .then(null, function(err) {
            // Log any errors.
            console.log(err);
        });
    });
  }

  // Add an event listener to the 'auth-button'.
  document.getElementById('auth-button').addEventListener('click', authorize);
</script>

<script src="https://apis.google.com/js/client.js?onload=authorize"></script>

</body>
</html>