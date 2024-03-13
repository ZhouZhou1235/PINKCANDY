/*
 * ZZWW 首页JS脚本
 * 版权所有 粉糖粒子周周
 * zhouzhouwebworld.online
 * copyright by pinkcandyzhou
 */

function welcome(){
    // 控制台欢迎
    var longtxt = `
    +-+-+-+-+-+-+-+-+-+-+-+-+-+
    |P|I|N|K|C|A|N|D|Y|Z|H|O|U|
    +-+-+-+-+-+-+-+-+-+-+-+-+-+
    `;
    var sayHi = "欢迎来到粉糖粒子，让我们堆积更多的毛绒绒吧！";
    let style = "color:pink;font-size:2em;";
    let c = "%c";
    console.log(c+longtxt,style);
    console.log(c+sayHi,style);
}
welcome();

function drawImg(img,imgSrc,theCanvas,ctx){
    var img = new Image();
    img.src = imgSrc;
    var theCanvas = document.getElementById(theCanvas);
    var ctx = theCanvas.getContext("2d");
    img.onload=()=>{
        theCanvas.width = img.width;
        theCanvas.height = img.height;
        ctx.drawImage(img,0,0,theCanvas.width,theCanvas.height);
    }
    ctx.drawImage(img,0,0,theCanvas.width,theCanvas.height);
    return;
}