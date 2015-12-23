(function(){var root=this;var previousUnderscore=root._;var breaker={};var ArrayProto=Array.prototype,ObjProto=Object.prototype,FuncProto=Function.prototype;var getTime=Date.now||function(){return(new Date).getTime()};var push=ArrayProto.push,slice=ArrayProto.slice,concat=ArrayProto.concat,toString=ObjProto.toString,hasOwnProperty=ObjProto.hasOwnProperty;var nativeForEach=ArrayProto.forEach,nativeMap=ArrayProto.map,nativeReduce=ArrayProto.reduce,nativeReduceRight=ArrayProto.reduceRight,nativeFilter=ArrayProto.filter,nativeEvery=ArrayProto.every,nativeSome=ArrayProto.some,nativeIndexOf=ArrayProto.indexOf,nativeLastIndexOf=ArrayProto.lastIndexOf,nativeIsArray=Array.isArray,nativeKeys=Object.keys,nativeBind=FuncProto.bind;var _=function(obj){if(obj instanceof _)return obj;if(!(this instanceof _))return new _(obj);this._wrapped=obj};if(typeof exports!=="undefined"){if(typeof module!=="undefined"&&module.exports){exports=module.exports=_}exports._=_}else{root._=_}_.VERSION="1.5.2";var each=_.each=_.forEach=function(obj,iterator,context){if(obj==null)return;if(nativeForEach&&obj.forEach===nativeForEach){obj.forEach(iterator,context)}else if(obj.length===+obj.length){for(var i=0,length=obj.length;i<length;i++){if(iterator.call(context,obj[i],i,obj)===breaker)return}}else{var keys=_.keys(obj);for(var i=0,length=keys.length;i<length;i++){if(iterator.call(context,obj[keys[i]],keys[i],obj)===breaker)return}}};_.map=_.collect=function(obj,iterator,context){var results=[];if(obj==null)return results;if(nativeMap&&obj.map===nativeMap)return obj.map(iterator,context);each(obj,function(value,index,list){results.push(iterator.call(context,value,index,list))});return results};var reduceError="Reduce of empty array with no initial value";_.reduce=_.foldl=_.inject=function(obj,iterator,memo,context){var initial=arguments.length>2;if(obj==null)obj=[];if(nativeReduce&&obj.reduce===nativeReduce){if(context)iterator=_.bind(iterator,context);return initial?obj.reduce(iterator,memo):obj.reduce(iterator)}each(obj,function(value,index,list){if(!initial){memo=value;initial=true}else{memo=iterator.call(context,memo,value,index,list)}});if(!initial)throw new TypeError(reduceError);return memo};_.reduceRight=_.foldr=function(obj,iterator,memo,context){var initial=arguments.length>2;if(obj==null)obj=[];if(nativeReduceRight&&obj.reduceRight===nativeReduceRight){if(context)iterator=_.bind(iterator,context);return initial?obj.reduceRight(iterator,memo):obj.reduceRight(iterator)}var length=obj.length;if(length!==+length){var keys=_.keys(obj);length=keys.length}each(obj,function(value,index,list){index=keys?keys[--length]:--length;if(!initial){memo=obj[index];initial=true}else{memo=iterator.call(context,memo,obj[index],index,list)}});if(!initial)throw new TypeError(reduceError);return memo};_.find=_.detect=function(obj,iterator,context){var result;any(obj,function(value,index,list){if(iterator.call(context,value,index,list)){result=value;return true}});return result};_.filter=_.select=function(obj,iterator,context){var results=[];if(obj==null)return results;if(nativeFilter&&obj.filter===nativeFilter)return obj.filter(iterator,context);each(obj,function(value,index,list){if(iterator.call(context,value,index,list))results.push(value)});return results};_.reject=function(obj,iterator,context){return _.filter(obj,function(value,index,list){return!iterator.call(context,value,index,list)},context)};_.every=_.all=function(obj,iterator,context){iterator||(iterator=_.identity);var result=true;if(obj==null)return result;if(nativeEvery&&obj.every===nativeEvery)return obj.every(iterator,context);each(obj,function(value,index,list){if(!(result=result&&iterator.call(context,value,index,list)))return breaker});return!!result};var any=_.some=_.any=function(obj,iterator,context){iterator||(iterator=_.identity);var result=false;if(obj==null)return result;if(nativeSome&&obj.some===nativeSome)return obj.some(iterator,context);each(obj,function(value,index,list){if(result||(result=iterator.call(context,value,index,list)))return breaker});return!!result};_.contains=_.include=function(obj,target){if(obj==null)return false;if(nativeIndexOf&&obj.indexOf===nativeIndexOf)return obj.indexOf(target)!=-1;return any(obj,function(value){return value===target})};_.invoke=function(obj,method){var args=slice.call(arguments,2);var isFunc=_.isFunction(method);return _.map(obj,function(value){return(isFunc?method:value[method]).apply(value,args)})};_.pluck=function(obj,key){return _.map(obj,function(value){return value[key]})};_.where=function(obj,attrs,first){if(_.isEmpty(attrs))return first?void 0:[];return _[first?"find":"filter"](obj,function(value){for(var key in attrs){if(attrs[key]!==value[key])return false}return true})};_.findWhere=function(obj,attrs){return _.where(obj,attrs,true)};_.max=function(obj,iterator,context){if(!iterator&&_.isArray(obj)&&obj[0]===+obj[0]&&obj.length<65535){return Math.max.apply(Math,obj)}if(!iterator&&_.isEmpty(obj))return-Infinity;var result={computed:-Infinity,value:-Infinity};each(obj,function(value,index,list){var computed=iterator?iterator.call(context,value,index,list):value;computed>result.computed&&(result={value:value,computed:computed})});return result.value};_.min=function(obj,iterator,context){if(!iterator&&_.isArray(obj)&&obj[0]===+obj[0]&&obj.length<65535){return Math.min.apply(Math,obj)}if(!iterator&&_.isEmpty(obj))return Infinity;var result={computed:Infinity,value:Infinity};each(obj,function(value,index,list){var computed=iterator?iterator.call(context,value,index,list):value;computed<result.computed&&(result={value:value,computed:computed})});return result.value};_.shuffle=function(obj){var rand;var index=0;var shuffled=[];each(obj,function(value){rand=_.random(index++);shuffled[index-1]=shuffled[rand];shuffled[rand]=value});return shuffled};_.sample=function(obj,n,guard){if(n==null||guard){if(obj.length!==+obj.length)obj=_.values(obj);return obj[_.random(obj.length-1)]}return _.shuffle(obj).slice(0,Math.max(0,n))};var lookupIterator=function(value){return _.isFunction(value)?value:function(obj){return obj[value]}};_.sortBy=function(obj,value,context){var iterator=value==null?_.identity:lookupIterator(value);return _.pluck(_.map(obj,function(value,index,list){return{value:value,index:index,criteria:iterator.call(context,value,index,list)}}).sort(function(left,right){var a=left.criteria;var b=right.criteria;if(a!==b){if(a>b||a===void 0)return 1;if(a<b||b===void 0)return-1}return left.index-right.index}),"value")};var group=function(behavior){return function(obj,value,context){var result={};var iterator=value==null?_.identity:lookupIterator(value);each(obj,function(value,index){var key=iterator.call(context,value,index,obj);behavior(result,key,value)});return result}};_.groupBy=group(function(result,key,value){(_.has(result,key)?result[key]:result[key]=[]).push(value)});_.indexBy=group(function(result,key,value){result[key]=value});_.countBy=group(function(result,key){_.has(result,key)?result[key]++:result[key]=1});_.sortedIndex=function(array,obj,iterator,context){iterator=iterator==null?_.identity:lookupIterator(iterator);var value=iterator.call(context,obj);var low=0,high=array.length;while(low<high){var mid=low+high>>>1;iterator.call(context,array[mid])<value?low=mid+1:high=mid}return low};_.toArray=function(obj){if(!obj)return[];if(_.isArray(obj))return slice.call(obj);if(obj.length===+obj.length)return _.map(obj,_.identity);return _.values(obj)};_.size=function(obj){if(obj==null)return 0;return obj.length===+obj.length?obj.length:_.keys(obj).length};_.first=_.head=_.take=function(array,n,guard){if(array==null)return void 0;return n==null||guard?array[0]:slice.call(array,0,n)};_.initial=function(array,n,guard){return slice.call(array,0,array.length-(n==null||guard?1:n))};_.last=function(array,n,guard){if(array==null)return void 0;if(n==null||guard){return array[array.length-1]}else{return slice.call(array,Math.max(array.length-n,0))}};_.rest=_.tail=_.drop=function(array,n,guard){return slice.call(array,n==null||guard?1:n)};_.compact=function(array){return _.filter(array,_.identity)};var flatten=function(input,shallow,output){if(shallow&&_.every(input,_.isArray)){return concat.apply(output,input)}each(input,function(value){if(_.isArray(value)||_.isArguments(value)){shallow?push.apply(output,value):flatten(value,shallow,output)}else{output.push(value)}});return output};_.flatten=function(array,shallow){return flatten(array,shallow,[])};_.without=function(array){return _.difference(array,slice.call(arguments,1))};_.uniq=_.unique=function(array,isSorted,iterator,context){if(_.isFunction(isSorted)){context=iterator;iterator=isSorted;isSorted=false}var initial=iterator?_.map(array,iterator,context):array;var results=[];var seen=[];each(initial,function(value,index){if(isSorted?!index||seen[seen.length-1]!==value:!_.contains(seen,value)){seen.push(value);results.push(array[index])}});return results};_.union=function(){return _.uniq(_.flatten(arguments,true))};_.intersection=function(array){var rest=slice.call(arguments,1);return _.filter(_.uniq(array),function(item){return _.every(rest,function(other){return _.indexOf(other,item)>=0})})};_.difference=function(array){var rest=concat.apply(ArrayProto,slice.call(arguments,1));return _.filter(array,function(value){return!_.contains(rest,value)})};_.zip=function(){var length=_.max(_.pluck(arguments,"length").concat(0));var results=new Array(length);for(var i=0;i<length;i++){results[i]=_.pluck(arguments,""+i)}return results};_.object=function(list,values){if(list==null)return{};var result={};for(var i=0,length=list.length;i<length;i++){if(values){result[list[i]]=values[i]}else{result[list[i][0]]=list[i][1]}}return result};_.indexOf=function(array,item,isSorted){if(array==null)return-1;var i=0,length=array.length;if(isSorted){if(typeof isSorted=="number"){i=isSorted<0?Math.max(0,length+isSorted):isSorted}else{i=_.sortedIndex(array,item);return array[i]===item?i:-1}}if(nativeIndexOf&&array.indexOf===nativeIndexOf)return array.indexOf(item,isSorted);for(;i<length;i++)if(array[i]===item)return i;return-1};_.lastIndexOf=function(array,item,from){if(array==null)return-1;var hasIndex=from!=null;if(nativeLastIndexOf&&array.lastIndexOf===nativeLastIndexOf){return hasIndex?array.lastIndexOf(item,from):array.lastIndexOf(item)}var i=hasIndex?from:array.length;while(i--)if(array[i]===item)return i;return-1};_.range=function(start,stop,step){if(arguments.length<=1){stop=start||0;start=0}step=arguments[2]||1;var length=Math.max(Math.ceil((stop-start)/step),0);var idx=0;var range=new Array(length);while(idx<length){range[idx++]=start;start+=step}return range};var ctor=function(){};_.bind=function(func,context){var args,bound;if(nativeBind&&func.bind===nativeBind)return nativeBind.apply(func,slice.call(arguments,1));if(!_.isFunction(func))throw new TypeError;args=slice.call(arguments,2);return bound=function(){if(!(this instanceof bound))return func.apply(context,args.concat(slice.call(arguments)));ctor.prototype=func.prototype;var self=new ctor;ctor.prototype=null;var result=func.apply(self,args.concat(slice.call(arguments)));if(Object(result)===result)return result;return self}};_.partial=function(func){var args=slice.call(arguments,1);return function(){return func.apply(this,args.concat(slice.call(arguments)))}};_.bindAll=function(obj){var funcs=slice.call(arguments,1);if(funcs.length===0)throw new Error("bindAll must be passed function names");each(funcs,function(f){obj[f]=_.bind(obj[f],obj)});return obj};_.memoize=function(func,hasher){var memo={};hasher||(hasher=_.identity);return function(){var key=hasher.apply(this,arguments);return _.has(memo,key)?memo[key]:memo[key]=func.apply(this,arguments)}};_.delay=function(func,wait){var args=slice.call(arguments,2);return setTimeout(function(){return func.apply(null,args)},wait)};_.defer=function(func){return _.delay.apply(_,[func,1].concat(slice.call(arguments,1)))};_.throttle=function(func,wait,options){var context,args,result;var timeout=null;var previous=0;options||(options={});var later=function(){previous=options.leading===false?0:getTime();timeout=null;result=func.apply(context,args);context=args=null};return function(){var now=getTime();if(!previous&&options.leading===false)previous=now;var remaining=wait-(now-previous);context=this;args=arguments;if(remaining<=0){clearTimeout(timeout);timeout=null;previous=now;result=func.apply(context,args);context=args=null}else if(!timeout&&options.trailing!==false){timeout=setTimeout(later,remaining)}return result}};_.debounce=function(func,wait,immediate){var timeout,args,context,timestamp,result;return function(){context=this;args=arguments;timestamp=getTime();var later=function(){var last=getTime()-timestamp;if(last<wait){timeout=setTimeout(later,wait-last)}else{timeout=null;if(!immediate){result=func.apply(context,args);context=args=null}}};var callNow=immediate&&!timeout;if(!timeout){timeout=setTimeout(later,wait)}if(callNow){result=func.apply(context,args);context=args=null}return result}};_.once=function(func){var ran=false,memo;return function(){if(ran)return memo;ran=true;memo=func.apply(this,arguments);func=null;return memo}};_.wrap=function(func,wrapper){return _.partial(wrapper,func)};_.compose=function(){var funcs=arguments;return function(){var args=arguments;for(var i=funcs.length-1;i>=0;i--){args=[funcs[i].apply(this,args)]}return args[0]}};_.after=function(times,func){return function(){if(--times<1){return func.apply(this,arguments)}}};_.keys=nativeKeys||function(obj){if(obj!==Object(obj))throw new TypeError("Invalid object");var keys=[];for(var key in obj)if(_.has(obj,key))keys.push(key);return keys};_.values=function(obj){var keys=_.keys(obj);var length=keys.length;var values=new Array(length);for(var i=0;i<length;i++){values[i]=obj[keys[i]]}return values};_.pairs=function(obj){var keys=_.keys(obj);var length=keys.length;var pairs=new Array(length);for(var i=0;i<length;i++){pairs[i]=[keys[i],obj[keys[i]]]}return pairs};_.invert=function(obj){var result={};var keys=_.keys(obj);for(var i=0,length=keys.length;i<length;i++){result[obj[keys[i]]]=keys[i]}return result};_.functions=_.methods=function(obj){var names=[];for(var key in obj){if(_.isFunction(obj[key]))names.push(key)}return names.sort()};_.extend=function(obj){each(slice.call(arguments,1),function(source){if(source){for(var prop in source){obj[prop]=source[prop]}}});return obj};_.pick=function(obj){var copy={};var keys=concat.apply(ArrayProto,slice.call(arguments,1));each(keys,function(key){if(key in obj)copy[key]=obj[key]});return copy};_.omit=function(obj){var copy={};var keys=concat.apply(ArrayProto,slice.call(arguments,1));for(var key in obj){if(!_.contains(keys,key))copy[key]=obj[key]}return copy};_.defaults=function(obj){each(slice.call(arguments,1),function(source){if(source){for(var prop in source){if(obj[prop]===void 0)obj[prop]=source[prop]}}});return obj};_.clone=function(obj){if(!_.isObject(obj))return obj;return _.isArray(obj)?obj.slice():_.extend({},obj)};_.tap=function(obj,interceptor){interceptor(obj);return obj};var eq=function(a,b,aStack,bStack){if(a===b)return a!==0||1/a==1/b;if(a==null||b==null)return a===b;if(a instanceof _)a=a._wrapped;if(b instanceof _)b=b._wrapped;var className=toString.call(a);if(className!=toString.call(b))return false;switch(className){case"[object String]":return a==String(b);case"[object Number]":return a!=+a?b!=+b:a==0?1/a==1/b:a==+b;case"[object Date]":case"[object Boolean]":return+a==+b;case"[object RegExp]":return a.source==b.source&&a.global==b.global&&a.multiline==b.multiline&&a.ignoreCase==b.ignoreCase}if(typeof a!="object"||typeof b!="object")return false;var length=aStack.length;while(length--){if(aStack[length]==a)return bStack[length]==b}var aCtor=a.constructor,bCtor=b.constructor;if(aCtor!==bCtor&&!(_.isFunction(aCtor)&&aCtor instanceof aCtor&&_.isFunction(bCtor)&&bCtor instanceof bCtor)&&"constructor"in a&&"constructor"in b){return false}aStack.push(a);bStack.push(b);var size=0,result=true;if(className=="[object Array]"){size=a.length;result=size==b.length;if(result){while(size--){if(!(result=eq(a[size],b[size],aStack,bStack)))break}}}else{for(var key in a){if(_.has(a,key)){size++;if(!(result=_.has(b,key)&&eq(a[key],b[key],aStack,bStack)))break}}if(result){for(key in b){if(_.has(b,key)&&!size--)break}result=!size}}aStack.pop();bStack.pop();return result};_.isEqual=function(a,b){return eq(a,b,[],[])};_.isEmpty=function(obj){if(obj==null)return true;if(_.isArray(obj)||_.isString(obj))return obj.length===0;for(var key in obj)if(_.has(obj,key))return false;return true};_.isElement=function(obj){return!!(obj&&obj.nodeType===1)};_.isArray=nativeIsArray||function(obj){return toString.call(obj)=="[object Array]"};_.isObject=function(obj){return obj===Object(obj)};each(["Arguments","Function","String","Number","Date","RegExp"],function(name){_["is"+name]=function(obj){return toString.call(obj)=="[object "+name+"]"}});if(!_.isArguments(arguments)){_.isArguments=function(obj){return!!(obj&&_.has(obj,"callee"))}}if(typeof/./!=="function"){_.isFunction=function(obj){return typeof obj==="function"}}_.isFinite=function(obj){return isFinite(obj)&&!isNaN(parseFloat(obj))};_.isNaN=function(obj){return _.isNumber(obj)&&obj!=+obj};_.isBoolean=function(obj){return obj===true||obj===false||toString.call(obj)=="[object Boolean]"};_.isNull=function(obj){return obj===null};_.isUndefined=function(obj){return obj===void 0};_.has=function(obj,key){return hasOwnProperty.call(obj,key)};_.noConflict=function(){root._=previousUnderscore;return this};_.identity=function(value){return value};_.times=function(n,iterator,context){var accum=Array(Math.max(0,n));for(var i=0;i<n;i++)accum[i]=iterator.call(context,i);return accum};_.random=function(min,max){if(max==null){max=min;min=0}return min+Math.floor(Math.random()*(max-min+1))};var entityMap={escape:{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;"}};entityMap.unescape=_.invert(entityMap.escape);var entityRegexes={escape:new RegExp("["+_.keys(entityMap.escape).join("")+"]","g"),unescape:new RegExp("("+_.keys(entityMap.unescape).join("|")+")","g")};_.each(["escape","unescape"],function(method){_[method]=function(string){if(string==null)return"";return(""+string).replace(entityRegexes[method],function(match){return entityMap[method][match]})}});_.result=function(object,property){if(object==null)return void 0;var value=object[property];return _.isFunction(value)?value.call(object):value};_.mixin=function(obj){each(_.functions(obj),function(name){var func=_[name]=obj[name];_.prototype[name]=function(){var args=[this._wrapped];push.apply(args,arguments);return result.call(this,func.apply(_,args))}})};var idCounter=0;_.uniqueId=function(prefix){var id=++idCounter+"";return prefix?prefix+id:id};_.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var noMatch=/(.)^/;var escapes={"'":"'","\\":"\\","\r":"r","\n":"n","	":"t","\u2028":"u2028","\u2029":"u2029"};var escaper=/\\|'|\r|\n|\t|\u2028|\u2029/g;_.template=function(text,data,settings){var render;settings=_.defaults({},settings,_.templateSettings);var matcher=new RegExp([(settings.escape||noMatch).source,(settings.interpolate||noMatch).source,(settings.evaluate||noMatch).source].join("|")+"|$","g");var index=0;var source="__p+='";text.replace(matcher,function(match,escape,interpolate,evaluate,offset){source+=text.slice(index,offset).replace(escaper,function(match){return"\\"+escapes[match]});if(escape){source+="'+\n((__t=("+escape+"))==null?'':_.escape(__t))+\n'"}if(interpolate){source+="'+\n((__t=("+interpolate+"))==null?'':__t)+\n'"}if(evaluate){source+="';\n"+evaluate+"\n__p+='"}index=offset+match.length;return match});source+="';\n";if(!settings.variable)source="with(obj||{}){\n"+source+"}\n";source="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+source+"return __p;\n";try{render=new Function(settings.variable||"obj","_",source)}catch(e){e.source=source;throw e}if(data)return render(data,_);var template=function(data){return render.call(this,data,_)};template.source="function("+(settings.variable||"obj")+"){\n"+source+"}";return template};_.chain=function(obj){return _(obj).chain()};var result=function(obj){return this._chain?_(obj).chain():obj};_.mixin(_);each(["pop","push","reverse","shift","sort","splice","unshift"],function(name){var method=ArrayProto[name];_.prototype[name]=function(){var obj=this._wrapped;method.apply(obj,arguments);if((name=="shift"||name=="splice")&&obj.length===0)delete obj[0];return result.call(this,obj)}});each(["concat","join","slice"],function(name){var method=ArrayProto[name];_.prototype[name]=function(){return result.call(this,method.apply(this._wrapped,arguments))}});_.extend(_.prototype,{chain:function(){this._chain=true;return this},value:function(){return this._wrapped}});if(typeof define==="function"&&define.amd){define("underscore",[],function(){return _})}}).call(this);Request=Class.extend({init:function(method,IDs,fields){this.data="";var host=this.getHost(IDs[0]);var api_id=this.getApiId(IDs[0]);var IDString=IDs.join(",");var path="/2.0/"+method+"/info/?application_id="+api_id+"&clan_id="+IDString;if(fields){path+="&fields="+fields}var self=this;$.ajax({url:"http://"+host+path,dataType:"json",success:function(data){self.success_callback(data)},error:function(jqXHR,textStatus){self.error_callback(textStatus)}})},onSuccess:function(callback){this.success_callback=callback},onError:function(callback){this.error_callback=callback},getHost:function(id){if(id>3e9){return"api.worldoftanks.kr"}if(id>25e8){return"portal-wot.go.vn"}if(id>2e9){return"api.worldoftanks.asia"}if(id>1e9){return"api.worldoftanks.com"}if(id>5e8){return"api.worldoftanks.eu"}return"api.worldoftanks.ru"},getApiId:function(id){if(id>3e9){return"ffea0f1c3c5f770db09357d94fe6abfb"}if(id>25e8){return"?"}if(id>2e9){return"39b4939f5f2460b3285bfa708e4b252c"}if(id>1e9){return"16924c431c705523aae25b6f638c54dd"}if(id>5e8){return"d0a293dc77667c9328783d489c8cef73"}return"171745d21f7f98fd8878771da1000a31"}});ClanWorker=Class.extend({init:function(url,region){if(!url){if(!window.location.origin){this.url="ws://"+window.location.hostname+(window.location.port?":"+window.location.port:"")}else{this.url=location.origin.replace(/^http/,"ws")}}else{this.url=url}this.config={};this.options={version:3,type:"clans",region:region||1};this.connect();this.stats={finishedRequests:0,errorRequests:0,finishedClans:0,start:new Date};this.newTasks=[];this.currentRequests={};this.interval=null;this.paused=false;this.lastRequestAt=new Date;this.waitTime=2e3;this.sync={}},connect:function(){console.log("Connecting");var self=this;var ws=new WebSocket(this.url);ws.onopen=function(){console.log("Connected");self.ws=ws;self.sync.start=new Date;self.send(["client-worker",self.getQueueOptions()])};ws.onmessage=function(event){var msg=JSON.parse(event.data);self.handleMessage(msg)};ws.onclose=function(data){if(data.reason=="version"){console.log("Disconnected - incompatible server version");return}else if(data.reason=="client-limit"){console.log("Disconnected - too many clients connected to server");return}console.log("Reconnect in 1s",data.reason);setTimeout(function(){self.connect()},1e3)}},handleMessage:function(msg){var self=this;var action=msg.shift();if(action=="set-task"){var task=msg.shift();console.log("Task received:",task.ID);this.newTasks.push(task);if(this.interval===null){this.start()}if(this.paused){this.pause(false,true)}}else if(action=="execute"){var ID=msg.shift();var method=msg.shift();var data=this[method]?this[method].apply(this,msg):{error:"No such method"};this.send(["emit","executed."+ID,data])}else if(action=="executeAsync"){var ID=msg.shift();var method=msg.shift();msg.push(function(){var args=[];for(var i in arguments){args.push(arguments[i])}args.unshift("emit","executed."+ID);self.send(args)});if(this[method]){this[method].apply(this,msg)}else{this.send(["emit","executed."+ID,{error:"No such method"}])}}else if(action=="sync"){var time=msg.shift();self.sync.end=new Date;self.sync.duration=this.sync.end.getTime()-this.sync.start.getTime();self.sync.midpoint=new Date((this.sync.end.getTime()+this.sync.start.getTime())/2);self.sync.server=new Date(time);self.sync.offset=this.sync.server.getTime()-this.sync.midpoint.getTime()}else{console.log(action,msg)}},send:function(msg){if(!this.ws){console.log("Not connected")}else{this.ws.send(JSON.stringify(msg))}},start:function(silent){var self=this;this.paused=false;if(!silent){console.log("Worker started.");this.send(["emit","start",true])}this.interval=setInterval(function(){self.step()},10)},pause:function(pause,silent){if(pause&&!this.paused){clearInterval(this.interval);this.paused=true;this.silentPause=silent;if(!silent){console.log("Worker paused.");this.send(["emit","pause",true])}}else if(!pause&&this.paused){this.start(silent)}},getQueueOptions:function(){return this.options},getCurrentState:function(options){var ret={paused:this.paused&&!this.silentPause,stats:this.stats};if(options&&options.config){ret.config=this.getConfig()}return ret},getConfig:function(){var ret=this.config;ret.paused=this.paused&&!this.silentPause;return ret},setConfig:function(config){_.each(config,function(value,name){if(name!="paused"){this.config[name]=value}else{this.pause(value)}},this);return this.getConfig()},getNumberOfCurrentRequests:function(){var count=0;for(var i in this.currentRequests){count++}return count},step:function(){var sinceLastRequest=(new Date).getTime()-this.lastRequestAt.getTime();if(this.getNumberOfCurrentRequests()<this.config.maxActiveRequests&&sinceLastRequest>Math.max(this.waitTime,this.config.minWaitTime)){if(this.newTasks.length>0){this.loadClans(this.newTasks.shift())}else{this.pause(true,true);console.log("Worker ready");this.send(["emit","ready",this.options,false])}}},loadClans:function(task){var ID=task.ID;var self=this;var IDs=task.clans;this.addRequest(task);var req=new Request("clan",IDs,"description_html,abbreviation,motto,name,members.account_name");req.onSuccess(function(data){if(self.checkData(data,IDs)){self.finishRequest(task,false);self.send(["process-task",ID,data.data])}else{self.finishRequest(task,data.status+" "+JSON.stringify(data.error));console.log("Report fail",ID);self.send(["emit","fail-task",ID])}});req.onError(function(error){self.finishRequest(task,error);self.newTasks.unshift(task)})},syncedTime:function(){var time=new Date;time.setTime(time.getTime()+this.sync.offset);return time},addRequest:function(task){var ID=task.ID;this.lastRequestAt=new Date;this.currentRequests[ID]={start:this.syncedTime(),count:task.clans.length,task:{ID:ID,region:task.region,skip:task.skip},workerType:"client"};this.send(["emit","start-request",this.currentRequests[task.ID],true])},finishRequest:function(task,error){var ID=task.ID;this.currentRequests[ID].end=this.syncedTime();this.currentRequests[ID].duration=this.currentRequests[ID].end.getTime()-this.currentRequests[ID].start.getTime();this.currentRequests[ID].error=error;this.stats.finishedClans+=error?0:this.currentRequests[ID].count;this.stats.finishedRequests+=1;if(error){this.stats.errorRequests+=1}this.send(["emit","update",this.getCurrentState(),true]);this.waitTime=this.currentRequests[ID].duration*this.config.waitMultiplier/this.config.maxActiveRequests;this.send(["emit","finish-request",this.currentRequests[ID],true]);delete this.currentRequests[ID]},checkData:function(parsed,IDs){if(parsed.status=="ok"){for(var i in IDs){var ID=IDs[i];if(parsed.data[ID]===undefined){return false}}return true}else{return false}}});