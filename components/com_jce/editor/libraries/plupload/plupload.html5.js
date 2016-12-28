(function(window,document,plupload,undef){var fakeSafariDragDrop;function readFileAsDataURL(file,callback){var reader;if("FileReader"in window){reader=new FileReader();reader.readAsDataURL(file);reader.onload=function(){callback(reader.result)}}else{return callback(file.getAsDataURL())}}function readFileAsBinary(file,callback){var reader;if("FileReader"in window){reader=new FileReader();reader.readAsBinaryString(file);reader.onload=function(){callback(reader.result)}}else{return callback(file.getAsBinary())}}function scaleImage(image_file,resize,mime,callback){var canvas,context,img,scale;readFileAsDataURL(image_file,function(data){canvas=document.createElement("canvas");canvas.style.display='none';document.body.appendChild(canvas);context=canvas.getContext('2d');img=new Image();img.onerror=img.onabort=function(){callback({success:false})};img.onload=function(){var width,height,percentage,jpegHeaders,exifParser;if(!resize['width']){resize['width']=img.width}if(!resize['height']){resize['height']=img.height}scale=Math.min(resize.width/img.width,resize.height/img.height);if(scale<1||(scale===1&&mime==='image/jpeg')){width=Math.round(img.width*scale);height=Math.round(img.height*scale);canvas.width=width;canvas.height=height;context.drawImage(img,0,0,width,height);if(mime==='image/jpeg'){jpegHeaders=new JPEG_Headers(atob(data.substring(data.indexOf('base64,')+7)));if(jpegHeaders['headers']&&jpegHeaders['headers'].length){exifParser=new ExifParser();if(exifParser.init(jpegHeaders.get('exif')[0])){exifParser.setExif('PixelXDimension',width);exifParser.setExif('PixelYDimension',height);jpegHeaders.set('exif',exifParser.getBinary())}}if(resize['quality']){try{data=canvas.toDataURL(mime,resize['quality']/100)}catch(e){data=canvas.toDataURL(mime)}}}else{data=canvas.toDataURL(mime)}data=data.substring(data.indexOf('base64,')+7);data=atob(data);if(jpegHeaders['headers']&&jpegHeaders['headers'].length){data=jpegHeaders.restore(data);jpegHeaders.purge()}canvas.parentNode.removeChild(canvas);callback({success:true,data:data})}else{callback({success:false})}};img.src=data})}plupload.runtimes.Html5=plupload.addRuntime("html5",{getFeatures:function(){var xhr,hasXhrSupport,hasProgress,dataAccessSupport,sliceSupport,win=window;hasXhrSupport=hasProgress=dataAccessSupport=sliceSupport=false;if(win.XMLHttpRequest){xhr=new XMLHttpRequest();hasProgress=!!xhr.upload;hasXhrSupport=!!(xhr.sendAsBinary||xhr.upload)}if(hasXhrSupport){dataAccessSupport=!!(File&&(File.prototype.getAsDataURL||win.FileReader)&&xhr.sendAsBinary);sliceSupport=!!(File&&File.prototype.slice)}fakeSafariDragDrop=navigator.userAgent.indexOf('Safari')>0&&navigator.vendor.indexOf('Apple')!==-1;return{html5:hasXhrSupport,dragdrop:win.mozInnerScreenX!==undef||sliceSupport||fakeSafariDragDrop,jpgresize:dataAccessSupport,pngresize:dataAccessSupport,multipart:dataAccessSupport||!!win.FileReader||!!win.FormData,progress:hasProgress,chunks:sliceSupport||dataAccessSupport,canOpenDialog:navigator.userAgent.indexOf('WebKit')!==-1}},init:function(uploader,callback){var html5files={},features;function addSelectedFiles(native_files){var file,i,files=[],id,fileNames={};for(i=0;i<native_files.length;i++){file=native_files[i];if(fileNames[file.name]){continue}fileNames[file.name]=true;id=plupload.guid();html5files[id]=file;files.push(new plupload.File(id,file.fileName,file.fileSize||file.size))}if(files.length){uploader.trigger("FilesAdded",files)}}features=this.getFeatures();if(!features.html5){callback({success:false});return}uploader.bind("Init",function(up){var inputContainer,browseButton,mimes=[],i,y,filters=up.settings.filters,ext,type,container=document.body,inputFile;inputContainer=document.createElement('div');inputContainer.id=up.id+'_html5_container';plupload.extend(inputContainer.style,{position:'absolute',background:uploader.settings.shim_bgcolor||'transparent',width:'100px',height:'100px',overflow:'hidden',zIndex:99999,opacity:uploader.settings.shim_bgcolor?'':0});inputContainer.className='plupload html5';if(uploader.settings.container){container=document.getElementById(uploader.settings.container);if(plupload.getStyle(container,'position')==='static'){container.style.position='relative'}}container.appendChild(inputContainer);no_type_restriction:for(i=0;i<filters.length;i++){ext=filters[i].extensions.split(/,/);for(y=0;y<ext.length;y++){if(ext[y]==='*'){mimes=[];break no_type_restriction}type=plupload.mimeTypes[ext[y]];if(type){mimes.push(type)}}}inputContainer.innerHTML='<input id="'+uploader.id+'_html5" '+'style="width:100%;height:100%;font-size:99px" type="file" accept="'+mimes.join(',')+'" '+(uploader.settings.multi_selection?'multiple="multiple"':'')+' />';inputFile=document.getElementById(uploader.id+'_html5');inputFile.onchange=function(){addSelectedFiles(this.files);this.value=''};browseButton=document.getElementById(up.settings.browse_button);if(browseButton){var hoverClass=up.settings.browse_button_hover,activeClass=up.settings.browse_button_active,topElement=up.features.canOpenDialog?browseButton:inputContainer;if(hoverClass){plupload.addEvent(topElement,'mouseover',function(){plupload.addClass(browseButton,hoverClass)},up.id);plupload.addEvent(topElement,'mouseout',function(){plupload.removeClass(browseButton,hoverClass)},up.id)}if(activeClass){plupload.addEvent(topElement,'mousedown',function(){plupload.addClass(browseButton,activeClass)},up.id);plupload.addEvent(document.body,'mouseup',function(){plupload.removeClass(browseButton,activeClass)},up.id)}if(up.features.canOpenDialog){plupload.addEvent(browseButton,'click',function(e){document.getElementById(up.id+'_html5').click();e.preventDefault()},up.id)}}});uploader.bind("PostInit",function(){var dropElm=document.getElementById(uploader.settings.drop_element);if(dropElm){if(fakeSafariDragDrop){plupload.addEvent(dropElm,'dragenter',function(e){var dropInputElm,dropPos,dropSize;dropInputElm=document.getElementById(uploader.id+"_drop");if(!dropInputElm){dropInputElm=document.createElement("input");dropInputElm.setAttribute('type',"file");dropInputElm.setAttribute('id',uploader.id+"_drop");dropInputElm.setAttribute('multiple','multiple');plupload.addEvent(dropInputElm,'change',function(){addSelectedFiles(this.files);plupload.removeEvent(dropInputElm,'change',uploader.id);dropInputElm.parentNode.removeChild(dropInputElm)},uploader.id);dropElm.appendChild(dropInputElm)}dropPos=plupload.getPos(dropElm,document.getElementById(uploader.settings.container));dropSize=plupload.getSize(dropElm);if(plupload.getStyle(dropElm,'position')==='static'){plupload.extend(dropElm.style,{position:'relative'})}plupload.extend(dropInputElm.style,{position:'absolute',display:'block',top:0,left:0,width:dropSize.w+'px',height:dropSize.h+'px',opacity:0})},uploader.id);return}plupload.addEvent(dropElm,'dragover',function(e){e.preventDefault()},uploader.id);plupload.addEvent(dropElm,'drop',function(e){var dataTransfer=e.dataTransfer;if(dataTransfer&&dataTransfer.files){addSelectedFiles(dataTransfer.files)}e.preventDefault()},uploader.id)}});uploader.bind("Refresh",function(up){var browseButton,browsePos,browseSize,inputContainer,pzIndex;browseButton=document.getElementById(uploader.settings.browse_button);if(browseButton){browsePos=plupload.getPos(browseButton,document.getElementById(up.settings.container));browseSize=plupload.getSize(browseButton);inputContainer=document.getElementById(uploader.id+'_html5_container');plupload.extend(inputContainer.style,{top:browsePos.y+'px',left:browsePos.x+'px',width:browseSize.w+'px',height:browseSize.h+'px'});if(uploader.features.canOpenDialog){pzIndex=parseInt(browseButton.parentNode.style.zIndex,10);if(isNaN(pzIndex)){pzIndex=0}plupload.extend(browseButton.style,{zIndex:pzIndex});if(plupload.getStyle(browseButton,'position')==='static'){plupload.extend(browseButton.style,{position:'relative'})}plupload.extend(inputContainer.style,{zIndex:pzIndex-1})}}});uploader.bind("UploadFile",function(up,file){var settings=up.settings,nativeFile,resize;function sendBinaryBlob(blob){var chunk=0,loaded=0;function uploadNextChunk(){var chunkBlob=blob,xhr,upload,chunks,args,multipartDeltaSize=0,boundary='----pluploadboundary'+plupload.guid(),chunkSize,curChunkSize,formData,dashdash='--',crlf='\r\n',multipartBlob='',mimeType,url=up.settings.url;if(file.status==plupload.DONE||file.status==plupload.FAILED||up.state==plupload.STOPPED){return}args={name:file.target_name||file.name};if(settings.chunk_size&&features.chunks){chunkSize=settings.chunk_size;chunks=Math.ceil(file.size/chunkSize);curChunkSize=Math.min(chunkSize,file.size-(chunk*chunkSize));if(typeof(blob)=='string'){chunkBlob=blob.substring(chunk*chunkSize,chunk*chunkSize+curChunkSize)}else{chunkBlob=blob.slice(chunk*chunkSize,curChunkSize)}args.chunk=chunk;args.chunks=chunks}else{curChunkSize=file.size}xhr=new XMLHttpRequest();upload=xhr.upload;if(upload){upload.onprogress=function(e){file.loaded=Math.min(file.size,loaded+e.loaded-multipartDeltaSize);up.trigger('UploadProgress',file)}}if(!up.settings.multipart||!features.multipart){url=plupload.buildUrl(up.settings.url,args)}else{args.name=file.target_name||file.name}xhr.open("post",url,true);xhr.onreadystatechange=function(){var httpStatus,chunkArgs;if(xhr.readyState==4){try{httpStatus=xhr.status}catch(ex){httpStatus=0}if(httpStatus>=400){up.trigger('Error',{code:plupload.HTTP_ERROR,message:plupload.translate('HTTP Error.'),file:file,status:httpStatus})}else{if(chunks){chunkArgs={chunk:chunk,chunks:chunks,response:xhr.responseText,status:httpStatus};up.trigger('ChunkUploaded',file,chunkArgs);loaded+=curChunkSize;if(chunkArgs.cancelled){file.status=plupload.FAILED;return}file.loaded=Math.min(file.size,(chunk+1)*chunkSize)}else{file.loaded=file.size}up.trigger('UploadProgress',file);if(!chunks||++chunk>=chunks){file.status=plupload.DONE;up.trigger('FileUploaded',file,{response:xhr.responseText,status:httpStatus});nativeFile=blob=html5files[file.id]=null}else{uploadNextChunk()}}xhr=chunkBlob=formData=multipartBlob=null}};plupload.each(up.settings.headers,function(value,name){xhr.setRequestHeader(name,value)});if(up.settings.multipart&&features.multipart){if(!xhr.sendAsBinary||fakeSafariDragDrop){formData=new FormData();plupload.each(plupload.extend(args,up.settings.multipart_params),function(value,name){formData.append(name,value)});formData.append(up.settings.file_data_name,chunkBlob);xhr.send(formData);return}xhr.setRequestHeader('Content-Type','multipart/form-data; boundary='+boundary);plupload.each(plupload.extend(args,up.settings.multipart_params),function(value,name){multipartBlob+=dashdash+boundary+crlf+'Content-Disposition: form-data; name="'+name+'"'+crlf+crlf;multipartBlob+=unescape(encodeURIComponent(value))+crlf});mimeType=plupload.mimeTypes[file.name.replace(/^.+\.([^.]+)/,'$1').toLowerCase()]||'application/octet-stream';multipartBlob+=dashdash+boundary+crlf+'Content-Disposition: form-data; name="'+up.settings.file_data_name+'"; filename="'+unescape(encodeURIComponent(file.name))+'"'+crlf+'Content-Type: '+mimeType+crlf+crlf+chunkBlob+crlf+dashdash+boundary+dashdash+crlf;multipartDeltaSize=multipartBlob.length-chunkBlob.length;chunkBlob=multipartBlob}else{xhr.setRequestHeader('Content-Type','application/octet-stream')}if(xhr.sendAsBinary){xhr.sendAsBinary(chunkBlob)}else{xhr.send(chunkBlob)}}uploadNextChunk()}nativeFile=html5files[file.id];resize=up.settings.resize;if(features.jpgresize){if(resize&&/\.(png|jpg|jpeg)$/i.test(file.name)){scaleImage(nativeFile,resize,/\.png$/i.test(file.name)?'image/png':'image/jpeg',function(res){if(res.success){file.size=res.data.length;sendBinaryBlob(res.data)}else{readFileAsBinary(nativeFile,sendBinaryBlob)}});readFileAsBinary(nativeFile,sendBinaryBlob)}else{readFileAsBinary(nativeFile,sendBinaryBlob)}}else{sendBinaryBlob(nativeFile)}});uploader.bind('Destroy',function(up){var name,element,container=document.body,elements={inputContainer:up.id+'_html5_container',inputFile:up.id+'_html5',browseButton:up.settings.browse_button,dropElm:up.settings.drop_element};for(name in elements){element=document.getElementById(elements[name]);if(element){plupload.removeAllEvents(element,up.id)}}plupload.removeAllEvents(document.body,up.id);if(up.settings.container){container=document.getElementById(up.settings.container)}container.removeChild(document.getElementById(elements.inputContainer))});callback({success:true})}});function BinaryReader(){var II=false,bin;function read(idx,size){var mv=II?0:-8*(size-1),sum=0,i;for(i=0;i<size;i++){sum|=(bin.charCodeAt(idx+i)<<Math.abs(mv+i*8))}return sum}function putstr(segment,idx,length){var length=arguments.length===3?length:bin.length-idx-1;bin=bin.substr(0,idx)+segment+bin.substr(length+idx)}function write(idx,num,size){var str='',mv=II?0:-8*(size-1),i;for(i=0;i<size;i++){str+=String.fromCharCode((num>>Math.abs(mv+i*8))&255)}putstr(str,idx,size)}return{II:function(order){if(order===undef){return II}else{II=order}},init:function(binData){II=false;bin=binData},SEGMENT:function(idx,length,segment){switch(arguments.length){case 1:return bin.substr(idx,bin.length-idx-1);case 2:return bin.substr(idx,length);case 3:putstr(segment,idx,length);break;default:return bin}},BYTE:function(idx){return read(idx,1)},SHORT:function(idx){return read(idx,2)},LONG:function(idx,num){if(num===undef){return read(idx,4)}else{write(idx,num,4)}},SLONG:function(idx){var num=read(idx,4);return(num>2147483647?num-4294967296:num)},STRING:function(idx,size){var str='';for(size+=idx;idx<size;idx++){str+=String.fromCharCode(read(idx,1))}return str}}}function JPEG_Headers(data){var markers={0xFFE1:{app:'EXIF',name:'APP1',signature:"Exif\0"},0xFFE2:{app:'ICC',name:'APP2',signature:"ICC_PROFILE\0"},0xFFED:{app:'IPTC',name:'APP13',signature:"Photoshop 3.0\0"}},headers=[],read,idx,marker=undef,length=0,limit;read=new BinaryReader();read.init(data);if(read.SHORT(0)!==0xFFD8){return}idx=2;limit=Math.min(1048576,data.length);while(idx<=limit){marker=read.SHORT(idx);if(marker>=0xFFD0&&marker<=0xFFD7){idx+=2;continue}if(marker===0xFFDA||marker===0xFFD9){break}length=read.SHORT(idx+2)+2;if(markers[marker]&&read.STRING(idx+4,markers[marker].signature.length)===markers[marker].signature){headers.push({hex:marker,app:markers[marker].app.toUpperCase(),name:markers[marker].name.toUpperCase(),start:idx,length:length,segment:read.SEGMENT(idx,length)})}idx+=length}read.init(null);return{headers:headers,restore:function(data){read.init(data);if(read.SHORT(0)!==0xFFD8){return false}idx=read.SHORT(2)==0xFFE0?4+read.SHORT(4):2;for(var i=0,max=headers.length;i<max;i++){read.SEGMENT(idx,0,headers[i].segment);idx+=headers[i].length}return read.SEGMENT()},get:function(app){var array=[];for(var i=0,max=headers.length;i<max;i++){if(headers[i].app===app.toUpperCase()){array.push(headers[i].segment)}}return array},set:function(app,segment){var array=[];if(typeof(segment)==='string'){array.push(segment)}else{array=segment}for(var i=ii=0,max=headers.length;i<max;i++){if(headers[i].app===app.toUpperCase()){headers[i].segment=array[ii];headers[i].length=array[ii].length;ii++}if(ii>=array.length)break}},purge:function(){headers=[];read.init(null)}}}function ExifParser(){var data,tags,offsets={},tagDescs;data=new BinaryReader();tags={tiff:{0x0112:'Orientation',0x8769:'ExifIFDPointer',0x8825:'GPSInfoIFDPointer'},exif:{0x9000:'ExifVersion',0xA001:'ColorSpace',0xA002:'PixelXDimension',0xA003:'PixelYDimension',0x9003:'DateTimeOriginal',0x829A:'ExposureTime',0x829D:'FNumber',0x8827:'ISOSpeedRatings',0x9201:'ShutterSpeedValue',0x9202:'ApertureValue',0x9207:'MeteringMode',0x9208:'LightSource',0x9209:'Flash',0xA402:'ExposureMode',0xA403:'WhiteBalance',0xA406:'SceneCaptureType',0xA404:'DigitalZoomRatio',0xA408:'Contrast',0xA409:'Saturation',0xA40A:'Sharpness'},gps:{0x0000:'GPSVersionID',0x0001:'GPSLatitudeRef',0x0002:'GPSLatitude',0x0003:'GPSLongitudeRef',0x0004:'GPSLongitude'}};tagDescs={'ColorSpace':{1:'sRGB',0:'Uncalibrated'},'MeteringMode':{0:'Unknown',1:'Average',2:'CenterWeightedAverage',3:'Spot',4:'MultiSpot',5:'Pattern',6:'Partial',255:'Other'},'LightSource':{1:'Daylight',2:'Fliorescent',3:'Tungsten',4:'Flash',9:'Fine weather',10:'Cloudy weather',11:'Shade',12:'Daylight fluorescent (D 5700 - 7100K)',13:'Day white fluorescent (N 4600 -5400K)',14:'Cool white fluorescent (W 3900 - 4500K)',15:'White fluorescent (WW 3200 - 3700K)',17:'Standard light A',18:'Standard light B',19:'Standard light C',20:'D55',21:'D65',22:'D75',23:'D50',24:'ISO studio tungsten',255:'Other'},'Flash':{0x0000:'Flash did not fire.',0x0001:'Flash fired.',0x0005:'Strobe return light not detected.',0x0007:'Strobe return light detected.',0x0009:'Flash fired, compulsory flash mode',0x000D:'Flash fired, compulsory flash mode, return light not detected',0x000F:'Flash fired, compulsory flash mode, return light detected',0x0010:'Flash did not fire, compulsory flash mode',0x0018:'Flash did not fire, auto mode',0x0019:'Flash fired, auto mode',0x001D:'Flash fired, auto mode, return light not detected',0x001F:'Flash fired, auto mode, return light detected',0x0020:'No flash function',0x0041:'Flash fired, red-eye reduction mode',0x0045:'Flash fired, red-eye reduction mode, return light not detected',0x0047:'Flash fired, red-eye reduction mode, return light detected',0x0049:'Flash fired, compulsory flash mode, red-eye reduction mode',0x004D:'Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected',0x004F:'Flash fired, compulsory flash mode, red-eye reduction mode, return light detected',0x0059:'Flash fired, auto mode, red-eye reduction mode',0x005D:'Flash fired, auto mode, return light not detected, red-eye reduction mode',0x005F:'Flash fired, auto mode, return light detected, red-eye reduction mode'},'ExposureMode':{0:'Auto exposure',1:'Manual exposure',2:'Auto bracket'},'WhiteBalance':{0:'Auto white balance',1:'Manual white balance'},'SceneCaptureType':{0:'Standard',1:'Landscape',2:'Portrait',3:'Night scene'},'Contrast':{0:'Normal',1:'Soft',2:'Hard'},'Saturation':{0:'Normal',1:'Low saturation',2:'High saturation'},'Sharpness':{0:'Normal',1:'Soft',2:'Hard'},'GPSLatitudeRef':{N:'North latitude',S:'South latitude'},'GPSLongitudeRef':{E:'East longitude',W:'West longitude'}};function extractTags(IFD_offset,tags2extract){var length=data.SHORT(IFD_offset),i,ii,tag,type,count,tagOffset,offset,value,values=[],hash={};for(i=0;i<length;i++){offset=tagOffset=IFD_offset+12*i+2;tag=tags2extract[data.SHORT(offset)];if(tag===undef){continue}type=data.SHORT(offset+=2);count=data.LONG(offset+=2);offset+=4;values=[];switch(type){case 1:case 7:if(count>4){offset=data.LONG(offset)+offsets.tiffHeader}for(ii=0;ii<count;ii++){values[ii]=data.BYTE(offset+ii)}break;case 2:if(count>4){offset=data.LONG(offset)+offsets.tiffHeader}hash[tag]=data.STRING(offset,count-1);continue;case 3:if(count>2){offset=data.LONG(offset)+offsets.tiffHeader}for(ii=0;ii<count;ii++){values[ii]=data.SHORT(offset+ii*2)}break;case 4:if(count>1){offset=data.LONG(offset)+offsets.tiffHeader}for(ii=0;ii<count;ii++){values[ii]=data.LONG(offset+ii*4)}break;case 5:offset=data.LONG(offset)+offsets.tiffHeader;for(ii=0;ii<count;ii++){values[ii]=data.LONG(offset+ii*4)/data.LONG(offset+ii*4+4)}break;case 9:offset=data.LONG(offset)+offsets.tiffHeader;for(ii=0;ii<count;ii++){values[ii]=data.SLONG(offset+ii*4)}break;case 10:offset=data.LONG(offset)+offsets.tiffHeader;for(ii=0;ii<count;ii++){values[ii]=data.SLONG(offset+ii*4)/data.SLONG(offset+ii*4+4)}break;default:continue}value=(count==1?values[0]:values);if(tagDescs.hasOwnProperty(tag)&&typeof value!='object'){hash[tag]=tagDescs[tag][value]}else{hash[tag]=value}}return hash}function getIFDOffsets(){var Tiff=undef,idx=offsets.tiffHeader;data.II(data.SHORT(idx)==0x4949);if(data.SHORT(idx+=2)!==0x002A){return false}offsets['IFD0']=offsets.tiffHeader+data.LONG(idx+=2);Tiff=extractTags(offsets['IFD0'],tags.tiff);offsets['exifIFD']=('ExifIFDPointer'in Tiff?offsets.tiffHeader+Tiff.ExifIFDPointer:undef);offsets['gpsIFD']=('GPSInfoIFDPointer'in Tiff?offsets.tiffHeader+Tiff.GPSInfoIFDPointer:undef);return true}function setTag(ifd,tag,value){var offset,length,tagOffset,valueOffset=0;if(typeof(tag)==='string'){var tmpTags=tags[ifd.toLowerCase()];for(hex in tmpTags){if(tmpTags[hex]===tag){tag=hex;break}}}offset=offsets[ifd.toLowerCase()+'IFD'];length=data.SHORT(offset);for(i=0;i<length;i++){tagOffset=offset+12*i+2;if(data.SHORT(tagOffset)==tag){valueOffset=tagOffset+8;break}}if(!valueOffset)return false;data.LONG(valueOffset,value);return true}return{init:function(segment){offsets={tiffHeader:10};if(segment===undef||!segment.length){return false}data.init(segment);if(data.SHORT(0)===0xFFE1&&data.STRING(4,5).toUpperCase()==="EXIF\0"){return getIFDOffsets()}return false},EXIF:function(){var Exif;Exif=extractTags(offsets.exifIFD,tags.exif);Exif.ExifVersion=String.fromCharCode(Exif.ExifVersion[0],Exif.ExifVersion[1],Exif.ExifVersion[2],Exif.ExifVersion[3]);return Exif},GPS:function(){var GPS;GPS=extractTags(offsets.gpsIFD,tags.gps);GPS.GPSVersionID=GPS.GPSVersionID.join('.');return GPS},setExif:function(tag,value){if(tag!=='PixelXDimension'&&tag!=='PixelYDimension')return false;return setTag('exif',tag,value)},getBinary:function(){return data.SEGMENT()}}}})(window,document,plupload);