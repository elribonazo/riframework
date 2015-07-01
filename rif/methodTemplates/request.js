rifXhr.{{functionName}} = function {{functionName}}(parameters,callback){
	var required = {{required}};
	for(var i in required){
		if(!parameters.hasOwnProperty(required[i])){
			return callback({'error':true,'message':'Undefined required property ' + required[i]});
		}
	}
	var boundary = '---------------------------' + (new Date).getTime();
	var data = "";
	var xhr = new XMLHttpRequest();
	for(var param in parameters){
		data += "--" + boundary + "\r\n";
		data += 'Content-Disposition: form-data; name="' + param + '"\r\n\r\n'
		data += parameters[param] + '\r\n';
	}
	data += "--" + boundary + "--\r\n";
	xhr.open('{{requestType}}','{{requestUrl}}',true);
	xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + boundary);

	xhr.onload = function (e) {
	  if (xhr.readyState === 4) {
	    if (xhr.status === 200) {
	      return callback(JSON.parse(xhr.responseText))
	    } else {
	      return callback({success:false,error:xhr.status})
	    }
	  }
	};
	xhr.onerror = function (e) {
	  console.error(xhr.statusText);
	};
	xhr.send(data);
}