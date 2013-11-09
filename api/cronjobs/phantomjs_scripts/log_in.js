// Catch any errors
phantom.onError = function(msg, trace) {
    var msgStack = ['PHANTOM ERROR: ' + msg];
    if (trace && trace.length) {
        msgStack.push('TRACE:');
        trace.forEach(function(t) {
            msgStack.push(' -> ' + (t.file || t.sourceURL) + ': ' + t.line + (t.function ? ' (in function ' + t.function + ')' : ''));
        });
    }
    console.error(msgStack.join('\n'));
    phantom.exit(1);
};

var system = require("system");
if (system.args.length < 3) {
    console.log("Usage: log_in.js <username> <password>");
    phantom.exit(1);
}

serialize = function(data) {
  var str = [];
  for (var key in data) {
    str.push(encodeURIComponent(key)+"="+encodeURIComponent(data[key]));
  }
  return str.join("&");
}

// Fitbit, if you block this then I'll find another way...
// don't you worry.
var username = system.args[1];
var password = system.args[2];
var url = 'https://www.fitbit.com/login';
var data = {
  'login':                  'Log In',
  'includeWorkflow':        '',
  'loginRedirect':          'redirect',
  'disableThirdPartyLogin': 'false',
  'email':                  username,
  'password':               password,
  'rememberMe':             'true'
};

var page = require('webpage').create();
page.open(url, 'post', serialize(data), function(status) {
  // Catch any errors
  page.onError = function(msg, trace) {
    var msgStack = ['ERROR: ' + msg];
    if (trace && trace.length) {
        msgStack.push('TRACE:');
        trace.forEach(function(t) {
            msgStack.push(' -> ' + t.file + ': ' + t.line + (t.function ? ' (in function "' + t.function + '")' : ''));
        });
    }
    console.error(msgStack.join('\n'));
    phantom.exit(1);
  };

  // Check it
  if (status !== 'success') {
    console.log("Could not load page " + url);
    phantom.exit(1);
  } else {
    var loggedIn = page.evaluate(function () {
      return (document.getElementById("loginForm") == null);
    });
    if (!loggedIn) {
      console.log("Incorrect log in with email " + username);
      phantom.exit(1);
    } else {
      phantom.exit(0);
    }
  }
});