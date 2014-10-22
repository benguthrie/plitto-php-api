<!doctype html>
<html>
  <head>
    <title>Plitto API Guide</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  </head>

<body>
  <h1>About the Plitto API</h1>
  <p>The Plitto API is what the Plitto front end uses to populate itself with data. All the parameters are passed through the URL using mod_rewrite, which means that the paramaters look like they are folders within a directory, but really, they'res just passing variables.</p>
  <p>The first part of the URL after <b>/api</b> is the API command. Each API call in the documentation will have it's own API call.</p>
  <h1>API Standards</h1>
  <ul>
    <li>API labels are case-insensative</li>
  </ul>
  <script>
  var apiCalls = [];
// Log In with Facebook
  apiCalls.push(
  		{
  			name:"Login",
  			description:"This call uses the facebook ID to log into Plitto. The return is the Plitto user id. ",
  			sampleCall:'http://plitto.com/api/loginFB/4',
        input:[{name:'facebook user id',type:'integer'}],
        output:[{name:'plitto user id',type:'integer'}]
  		});
// Log Out
  apiCalls.push(
      {
        name:"Logout",
        description:"Destroys the session, removes the cookies, etc.",
        sampleCall:'http://plitto.com/api/logout',
        input:[{name:'',type:''}],
        output:[{name:'success',type:'true / false'}]
      });

    apiCalls.push(
      {
        name:"PlittoFriends",
        description:"Takes an input of a friends graph, and converts it to a Plitto session variable.",
        sampleCall:'http://plitto.com/api/pGraph',
        input:[
          {name:'graph',type:'array of user ids'},
          {name:'source',type:'Name of the source, like Facebook or Google, etc.'}
        ],
        output:[
          {name:'success',type:'true / false'},
          {name:'pGraph',type:'array of Plitto userids, and stores them as a session variable.'}
        ]
      });

// Show Lists
  apiCalls.push(
      {
        name:"Lists",
        description:"Returns lists, with context",
        sampleCall:'http://plitto.com/api/lists',
        input:[
          {name: "userq",type:"array - Array of Plitto User IDs to query with."},
          {name:"usertype", type:"string - p (Plitto) or fb(Facebook)"},
          {name:"listids", type:"array - optional"},
          {name:"listtype", type:"array - Filters the list type."},
          {name:"number", type:"integer - Selects the limit of the items"},
          {name:"order", type:"string - chooses how the items are ordered"},
          {name:"startfrom", type:"Selects where the results start from"}
        ],
        output:[
          {name:'lists',type:'object'}
        ]
      });

  var str = "";

  for(a = 0; a < apiCalls.length; a++){
    
    str += "<h1>" + apiCalls[a].name + "</h1>" + JSON.stringify(apiCalls[a],null,4);

    if(apiCalls[a].hasOwnProperty('sampleCall')){
      str +="<p><a href='" + apiCalls[a].sampleCall + "' target='_blank'>" + apiCalls[a].sampleCall + "</a></p>";

    }

    if(apiCalls[a].hasOwnProperty('description')){
      str +="<p>" + apiCalls[a].description + "</p>";

    }

    if(apiCalls[a].hasOwnProperty('input')){
      str +="<h3>Input</h3><ul>";
        for(b in apiCalls[a].input){
          str += "<li>Name: <strong>" + apiCalls[a].input[b]['name'] + "</strong><br/>Type: <strong>" + apiCalls[a].input[b]['type'] + "</strong></li>"; 
          
        }
      str +="</ul>";

    }

    if(apiCalls[a].hasOwnProperty('output')){
      str +="<h3>Output</h3><ul>";
        for(b in apiCalls[a].output){
          str += "<li>";

          if(apiCalls[a].output[b].hasOwnProperty('name')){
            str += "Name: <strong>" + apiCalls[a].output[b]['name'] + "</strong><br/>";

          }

          if(apiCalls[a].output[b].hasOwnProperty('type')){
            str += "Type: <strong>" + apiCalls[a].output[b]['type'] + "</strong>";

          }

          str+="</li>";
          
        }
      str +="</ul>";

    }

  }
  $("body").append(str);
  
  </script>

</body>

</html>