var vlaCalendar=new Class({
    slideDuration:500,
    fadeDuration:500,
    transition:Fx.Transitions.Quart.easeOut,
    startMonday:false,
    format:"d-m-y",
    separator:"-",
    filePath:"inc/",
    defaultView:"month",
    style:"",
    initialize:function(h,f){
        if(f){
            $extend(this,f)
        }
        this.loading=false;
        this.container=h=$(h);
        var e=this;
        var g="defaultView="+this.defaultView;
        if(this.picker){
            if($type(this.prefillDate)=="object"&&this.getInputDate(this.prefillDate)){
                g+="&pickedDate="+this.getInputDate(this.prefillDate)
            }
            if(this.linkWithInput){
                g+="&gotoPickedDate=1"
            }
        }
        this.u("base",g,function(){
            e.mainLoader=h.getElement("div[class=loaderA]");
            e.tempLoader=h.getElement("div[class=loaderB]");
            e.label=h.getElement("span[class=label]");
            e.arrowLeft=h.getElement("div[class=arrowLeft]");
            e.arrowRight=h.getElement("div[class=arrowRight]");
            e.initializeCalendarFunctions();
            if(e.picker){
                if($type(e.prefillDate)=="object"&&e.getInputDate(e.prefillDate)){
                    e.pick(e.prefillDate)
                }else{
                    if(e.prefillDate==true){
                        e.pick(JSON.decode(e.label.getProperty("date")))
                    }
                }
            }
        },h)
    },
    initializeCalendarFunctions:function(){
        this.resetArrows();
        var f=JSON.decode(this.mainLoader.getElement("table").getProperty("summary"));
        var e=this;
        this.label.removeClass("noHover").set("html",f.label).onclick=f.parent?function(){
            e.u(f.parent,"ts="+f.ts+"&parent="+f.current,function(){
                e.fade()
            })
        }:null;
        if(f.hide_left_arrow){
            this.hideLeftArrow()
        }else{
            if(f.hide_right_arrow){
                this.hideRightArrow()
            }
        }
        this.arrowLeft.onclick=function(){
            e.u(f.current,"ts="+f.pr_ts,function(){
                e.slideLeft()
            })
        };

        this.arrowRight.onclick=function(){
            e.u(f.current,"ts="+f.nx_ts,function(){
                e.slideRight()
            })
        };

        var d=this.mainLoader.getElements("td");
        switch(f.current){
            case"month":
                if(this.picker){
                    d.each(function(a){
                        a.onclick=function(){
                            e.pick(JSON.decode(a.getProperty("date")));
                            e.mainLoader.getElements("td").each(function(b){
                                b.removeClass("selected")
                            });
                            this.addClass("selected")
                        }
                    })
                }
                break;
            case"year":
                d.each(function(a){
                    a.onclick=function(){
                        e.u("month","ts="+a.getProperty("ts"),function(){
                            e.fade()
                        })
                    }
                });
                break;
            case"decade":
                this.label.addClass("noHover");
                d.each(function(a){
                    a.onclick=function(){
                        e.u("year","ts="+a.getProperty("ts")+"&m_ts="+a.getProperty("m_ts"),function(){
                            e.fade()
                        })
                    }
                });
                break
        }
    },
    u:function(j,g,k,l){
        if(!this.loading&&!this.transitioning){
            var h=this;
            this.loading=true;
            var m=$(l?l:this.tempLoader);
            g+="&picker="+(this.picker?1:0)+"&startMonday="+(this.startMonday?1:0)+"&style="+this.style;
            if(this.picker&&this.getInputDate()){
                g+="&pickedDate="+this.getInputDate()
            }
            new Request({
                method:"post",
                url:this.filePath+j+".php",
                onComplete:function(a){
                    m.set("html",a);
                    k();
                    h.loading=false
                }
            }).send(g)
        }
    },
    slideLeft:function(){
        var b=this;
        this.transitioning=true;
        this.tempLoader.setStyle("opacity",1).set("tween",{
            duration:this.slideDuration,
            transition:this.transition
        }).tween("margin-left",[-164,0]);
        this.mainLoader.setStyle("opacity",1).set("tween",{
            duration:this.slideDuration,
            transition:this.transition,
            onComplete:function(){
                b.transitioning=false
            }
        }).tween("margin-left",[0,164]);
        this.switchLoaders()
    },
    slideRight:function(){
        var b=this;
        this.transitioning=true;
        this.mainLoader.setStyle("opacity",1).set("tween",{
            duration:this.slideDuration,
            transition:this.transition
        }).tween("margin-left",[0,-164]);
        this.tempLoader.setStyle("opacity",1).set("tween",{
            duration:this.slideDuration,
            transition:this.transition,
            onComplete:function(){
                b.transitioning=false
            }
        }).tween("margin-left",[164,0]);
        this.switchLoaders()
    },
    fade:function(c){
        var d=this;
        this.transitioning=c?false:true;
        this.tempLoader.setStyles({
            opacity:0,
            "margin-left":0
        });
        this.mainLoader.set("tween",{
            duration:this.fadeDuration,
            transition:this.transition
        }).fade("out");
        this.tempLoader.set("tween",{
            duration:this.fadeDuration,
            transition:this.transition,
            onComplete:function(){
                d.tempLoader.setStyles({
                    opacity:1,
                    "margin-left":-999
                });
                d.transitioning=false
            }
        }).fade("in");
        this.switchLoaders()
    },
    switchLoaders:function(){
        this.mainLoader=this.mainLoader.className=="loaderA"?this.container.getElement("div[class=loaderB]"):this.container.getElement("div[class=loaderA]");
        this.tempLoader=this.tempLoader.className=="loaderA"?this.container.getElement("div[class=loaderB]"):this.container.getElement("div[class=loaderA]");
        this.initializeCalendarFunctions()
    },
    resetArrows:function(){
        this.arrowLeft.setStyle("visibility","visible");
        this.arrowRight.setStyle("visibility","visible")
    },
    hideLeftArrow:function(){
        this.arrowLeft.setStyle("visibility","hidden")
    },
    hideRightArrow:function(){
        this.arrowRight.setStyle("visibility","hidden")
    }
});
var vlaDatePicker=new Class({
    Extends:vlaCalendar,
    separateInput:false,
    prefillDate:false,
    linkWithInput:true,
    leadingZero:true,
    twoDigitYear:false,
    separator:"-",
    format:"d-m-y",
    openWith:null,
    alignX:"right",
    alignY:"inputTop",
    offset:{
        x:0,
        y:0
    },
    style:"",
    ieTransitionColor:"#ffffff",
    toggleDuration:350,
    initialize:function(d,e){
        if(e){
            $extend(this,e)
        }
        this.element=$(d);
        if(!this.element){
            throw"No (existing) element to create a datepicker for specified: new vlaDatePicker(ELEMENT, [options])"
        }
        if(this.separateInput){
            this.element.day=this.element.getElement("input[name="+this.separateInput.day+"]");
            this.element.month=this.element.getElement("input[name="+this.separateInput.month+"]");
            this.element.year=this.element.getElement("input[name="+this.separateInput.year+"]")
        }
        this.picker=new Element("div",{
            "class":"vlaCalendarPicker"+(this.style!=""?" "+this.style:"")
        }).injectTop($(document.body));
        this.pickerContent=new Element("div",{
            "class":"pickerBackground"
        }).injectTop(this.picker);
        this.parent(this.pickerContent);
        var f=this;
        (this.openWith?$(this.openWith):this.element).addEvent("focus",function(){
            f.show()
        }).addEvent("click",function(){
            f.openWith?f.toggle():f.show()
        }).addEvent("change",function(){
            f.hide()
        });
        document.addEvent("mousedown",function(a){
            if(f.outsideHide&&f.outsideClick(a,f.picker)){
                f.hide()
            }
        });
        if(this.linkWithInput){
            if(this.separateInput){
                this.element.day.addEvent("keyup",function(){
                    f.linkedUpdate()
                });
                this.element.month.addEvent("keyup",function(){
                    f.linkedUpdate()
                });
                this.element.year.addEvent("keyup",function(){
                    f.linkedUpdate()
                })
            }else{
                this.element.addEvent("keyup",function(){
                    f.linkedUpdate()
                })
            }
        }
        this.visible=false;
        this.outsideHide=false
    },
    position:function(){
        var f,d;
        switch(this.alignX){
            case"left":
                d=this.element.getLeft();
                break;
            case"center":
                var e=this.pickerContent.getStyle("width").toInt()/2;
                if(e==0){
                    e=83
                }
                d=this.element.getLeft()+(this.element.getSize().x/2)-e-((parseInt(this.pickerContent.getStyle("padding-left"))+parseInt(this.pickerContent.getStyle("padding-right")))/2);
                break;
            case"right":default:
                d=this.element.getLeft()+this.element.getSize().x;
                break
        }
        switch(this.alignY){
            case"bottom":
                f=this.getPos(this.element).y+this.element.getSize().y;
                break;
            case"top":
                f=this.getPos(this.element).y-parseInt(this.pickerContent.getStyle("height"))-(parseInt(this.pickerContent.getStyle("padding-top"))+parseInt(this.pickerContent.getStyle("padding-bottom")));
                break;
            case"inputTop":default:
                f=this.getPos(this.element).y
        }
        if(this.isNumber(this.offset.x)){
            d+=this.offset.x
        }
        if(this.isNumber(this.offset.y)){
            f+=this.offset.y
        }
        this.picker.setStyles({
            top:f,
            left:d
        })
    },
    show:function(){
        this.position();
        if(!this.visible){
            this.visible=true;
            var b=this;
            this.picker.setStyles({
                opacity:0,
                display:"inline"
            });
            if(Browser.Engine.trident5){
                this.picker.setStyle("background-color",this.ieTransitionColor)
            }
            this.picker.set("tween",{
                onComplete:function(){
                    if(Browser.Engine.trident5){
                        b.picker.setStyle("background-color","transparent")
                    }
                    b.outsideHide=true
                },
                duration:this.toggleDuration
            }).fade("in")
        }
    },
    hide:function(){
        if(this.visible){
            this.visible=false;
            var b=this;
            if(Browser.Engine.trident5){
                this.picker.setStyle("background-color",this.ieTransitionColor)
            }
            this.picker.set("tween",{
                onComplete:function(){
                    b.picker.setStyle("display","none");
                    b.outsideHide=false
                },
                duration:this.toggleDuration
            }).fade("out")
        }
    },
    toggle:function(){
        if(this.visible){
            this.hide()
        }else{
            this.show()
        }
    },
    pick:function(b){
        if(this.leadingZero){
            if(b.day<10){
                b.day="0"+b.day
            }
            if(b.month<10){
                b.month="0"+b.month
            }
        }
        if(this.twoDigitYear){
            b.year=b.year.toString().substring(2,4)
        }
        if(this.separateInput){
            if(this.element.day){
                this.element.day.set("value",b.day)
            }
            if(this.element.month){
                this.element.month.set("value",b.month)
            }
            if(this.element.year){
                this.element.year.set("value",b.year)
            }
            this.hide()
        }else{
            switch(this.format){
                case"m/d/y":
                    this.element.set("value",b.month+this.separator+b.day+this.separator+b.year);
                    break;
                case"y/m/d":
                    this.element.set("value",b.year+this.separator+b.month+this.separator+b.day);
                    break;
                case"y/d/m":
                    this.element.set("value",b.year+this.separator+b.day+this.separator+b.month);
                    break;
                case"d/m/y":default:
                    this.element.set("value",b.day+this.separator+b.month+this.separator+b.year)
            }
            this.hide()
        }
    },
    getInputDate:function(g){
        var f,h,j;
        if(g){
            f=g.day;
            h=g.month;
            j=g.year
        }else{
            if(this.separateInput){
                f=this.element.day.get("value").toInt();
                h=this.element.month.get("value").toInt();
                j=this.element.year.get("value").toInt()
            }else{
                var k=this.element.get("value").split(this.separator);
                if(k.length!=3){
                    return null
                }
                switch(this.format){
                    case"m/d/y":
                        f=k[1];
                        h=k[0];
                        j=k[2];
                        break;
                    case"y/m/d":
                        f=k[2];
                        h=k[1];
                        j=k[0];
                        break;
                    case"y/d/m":
                        f=k[1];
                        h=k[2];
                        j=k[0];
                        break;
                    case"d/m/y":default:
                        f=k[0];
                        h=k[1];
                        j=k[2]
                }
            }
        }
        if(!this.isNumber(f)||!this.isNumber(h)||!this.isNumber(j)||f==0||h==0||j=="0"||(this.twoDigitYear&&j>99)||(!this.twoDigitYear&&j<1979)||(!this.twoDigitYear&&j>2030)||h>12||f>31){
            return null
        }
        if(this.twoDigitYear&&this.isNumber(j)&&j<100){
            j=j.toInt();
            if(j<10){
                j="200"+j
            }else{
                if(j<70){
                    j="20"+j
                }else{
                    if(j>69){
                        j="19"+j
                    }else{
                        j=new Date().getFullYear()
                    }
                }
            }
        }
        return f+"/"+h+"/"+j
    },
    linkedUpdate:function(){
        var d=this;
        var c=this.getInputDate();
        if(c&&this.pickedDate!=c){
            this.u("month","gotoPickedDate=1",function(){
                d.fade(true)
            });
            this.pickedDate=c
        }
    },
    outsideClick:function(h,e){
        var f=this.getMousePos(h);
        var g=e.getCoordinates();
        return(f.x>g.left&&f.x<(g.left+g.width))&&(f.y>g.top&&f.y<(g.top+g.height))?false:true
    },
    getMousePos:function(b){
        if(document.all){
            return{
                x:window.event.clientX+window.getScrollLeft(),
                y:window.event.clientY+window.getScrollTop()
            }
        }else{
            return{
                x:b.page.x,
                y:b.page.y
            }
        }
    },
    isNumber:function(b){
        if(b==""){
            return false
        }
        return(b>=0)||(b<0)?true:false
    },
    getPos:function(d){
        var e,f=0;
        if(d.offsetParent){
            do{
                e+=d.offsetLeft;
                f+=d.offsetTop
            }while(d=d.offsetParent)
        }else{
            if(d.x){
                e+=d.x;
                f+=d.y
            }
        }
        return{
            x:e,
            y:f
        }
    }
});
Date.implement({
    getWeek:function(){
        var b=new Date(this.getFullYear(),0,1);
        return Math.ceil((((this-b)/86400000)+b.getDay())/7)
    },
    getTs:function(){
        return parseInt((this.getTime()/1000))
    },
    getMonthNo:function(){
        return(this.getMonth()+1)
    }
});
vlaCalendar.implement({
    weekDayLabels:["Mo","Tu","We","Th","Fr","Sa","Su"],
    monthLabels:["January","February","March","April","May","June","July","August","September","October","November","December"],
    monthSmallLabels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
    u:function(j,g,k,l){
        if(!this.loading&&!this.transitioning){
            this.loading=true;
            var h=this;
            var m=$(l?l:this.tempLoader);
            g+="&defaultView="+j;
            g+="&picker="+(this.picker?1:0)+"&startMonday="+(this.startMonday?1:0)+"&style="+this.style;
            if(this.picker&&this.getInputDate()){
                g+="&pickedDate="+this.getInputDate()
            }
            m.set("html",this.getHTML(g));
            k();
            this.loading=false
        }
    },
    getHTML:function(k){
        var j={};

        vars=k.split("&").each(function(a){
            param=a.split("=");
            if(!j[param[0]]){
                j[param[0]]=param[1]
            }
        });
        j.startMonday=j.startMonday.toInt();
        j.picker=j.picker.toInt();
        if(j.gotoPickedDate){
            j.gotoPickedDate=j.gotoPickedDate.toInt()
        }
        var h=null;
        if($defined(j.pickedDate)){
            if(j.pickedDate=="t"){
                h=this.mkdate();
                this.setDate(h)
            }else{
                match=j.pickedDate.split("/");
                if(match!=null){
                    if(match.length>0){
                        h=this.mkdate(match[2],match[1]-1,match[0]);
                        if(h.getMonthNo()!=match[1]){
                            h=this.mkdate(match[2],match[1]-1,this.daysInMonth(match[2],match[1]-1));
                            this.setDate(h)
                        }
                    }
                }
            }
        }
        if(h==null){
            h=this.mkdate()
        }
        if($defined(j.ts)){
            var f=new Date();
            f.setTime(parseInt(j.ts)*1000);
            ts=f.getTs()
        }else{
            ts=this.mktime(h.getFullYear(),h.getMonth(),1)
        }
        j.ts=ts;
        j.pickedDate=h;
        var g=null;
        if(j.defaultView=="decade"){
            g=this.getDecadeHTML(j)
        }else{
            if(j.defaultView=="year"){
                g=this.getYearHTML(j)
            }else{
                g=this.getMonthHTML(j)
            }
        }
        if(!$defined(this.createContainer)){
            this.createContainer=false;
            div='<div class="vlaCalendar'+($defined(k.style)?" "+k.style:"")+'">';
            div+='<span class="indication"><div class="arrowRight"></div><div class="arrowLeft"></div>';
            div+='<span class="label" date="';
            div+="{'day': '"+h.getDate()+"', 'month': '"+h.getMonthNo()+"'";
            div+=", 'year': '"+h.getFullYear()+"'}\">&nbsp;";
            div+="</span>";
            div+="</span>";
            div+='<div class="container">';
            div+='<div class="loaderB"></div>';
            div+='<div class="loaderA">'+g+"</div>";
            div+="</div>";
            div+="</div>";
            return div
        }else{
            return g
        }
    },
    getMonthHTML:function(v){
        var w=this.ts2date(v.ts);
        var z=w.getFullYear();
        var F=w.getMonth();
        var s=this.monthLabels[F];
        var E=this.daysInMonth(w.getFullYear(),w.getMonth());
        var x=this.mkdate(z,F-1,1);
        var A=this.mkdate(z,F+1,1);
        var D=w.getDay()-(v.startMonday?1:0);
        if(D==-1){
            D=6
        }
        w=w.getTs();
        var r='<table class="month'+(v.picker?" picker":"")+'" cellpadding="0" summary="{';
        r+="'ts': '"+w+"', 'pr_ts': '"+x.getTs()+"', 'nx_ts': '"+A.getTs()+"', 'label': '"+s+", "+z+"'";
        r+=", 'current': 'month', 'parent': 'year'";
        r+='}">';
        r+="<tr>";
        var C=6;
        if(v.startMonday){
            C=6
        }else{
            r+="<th>"+this.weekDayLabels[6]+"</th>";
            C=5
        }
        for(i=0;i<=C;i++){
            r+="<th>"+this.weekDayLabels[i]+"</th>"
        }
        r+="</tr>";
        r+='<tr class="firstRow">';
        var a=0;
        for(i=0;i<D;i++){
            C=this.daysInMonth(x.getFullYear(),x.getMonth());
            var u=C-(D-i)+1;
            i_date=this.mkdate(z,F-1,u,z);
            r+='<td class="outsideDay" date="{';
            r+="'day': '"+u+"', 'month': '"+i_date.getMonthNo()+"', 'year': '"+i_date.getFullYear()+"'}";
            r+='">'+u+"</td>"
        }
        var B=v.pickedDate.getTs();
        for(i=1;i<=E;i++){
            i_date=this.mkdate(z,F,i);
            i_ts=i_date.getTs();
            r+="<td"+(i_ts==B?' class="selected"':"");
            r+=" date=\"{'day': '"+i+"', 'month': '"+i_date.getMonthNo()+"', 'year': '"+z+"'}\">";
            r+=i;
            r+="</td>";
            if(D==6){
                week_num=i_date.getWeek()+1;
                r+="</tr><tr>";
                D=-1;
                a++
            }
            D++
        }
        var t=1;
        if(D!==0){
            for(i=D;i<7;i++){
                i_date=this.mkdate(z,F+1,t);
                i_ts=i_date.getTs();
                r+="<td class=\"outsideDay\" date=\"{'day': '"+t+"', 'month': '"+i_date.getMonthNo()+"', 'year': '"+i_date.getFullYear()+"'}\">"+t+"</td>";
                t++
            }
            a++
        }
        if(a==4||a==5){
            if(D!==0){
                r+="</tr><tr>"
            }
            for(i=0;i<(a==5?7:14);i++){
                i_date=this.mkdate(z,F+1,t);
                r+="<td class=\"outsideDay\" date=\"{'day': '"+t+"', 'month': '"+i_date.getMonthNo()+"', 'year': '"+i_date.getFullYear()+"'}\">"+t+"</td>";
                t++;
                if(i==6){
                    r+="</tr><tr>"
                }
            }
        }
        r+="</tr>";
        r+="</table>";
        return r
    },
    getYearHTML:function(o){
        var q=this.ts2date(o.ts);
        var m="";
        if(o.parent=="month"){
            m=this.mktime(q.getFullYear(),q.getMonth(),1)
        }
        var p=o.pickedDate;
        var r=q.getFullYear();
        var n=this.mktime(r-1,0,1);
        var t=this.mktime(r+1,0,1);
        q=q.getTs();
        var l='<table class="year" cellpadding="0" summary="';
        l+="{'ts': '"+q+"', 'pr_ts': '"+n+"', 'nx_ts': '"+t+"', 'label': '";
        l+=r+"', 'current': 'year', 'parent': 'decade'";
        l+='}">';
        var u=0;
        for(i=0;i<3;i++){
            l+="<tr>";
            for(y=0;y<4;y++){
                i_date=this.mkdate(r,u,1);
                i_ts=i_date.getTs();
                var s=(p.getMonthNo()==i_date.getMonthNo()&&p.getFullYear()==i_date.getFullYear());
                l+='<td ts="'+i_ts+'" class="'+(m==i_ts?"selected":"")+(s?"current":"")+'">'+this.monthSmallLabels[i_date.getMonth()]+"</td>";
                u++
            }
            l+="</tr>"
        }
        l+="</table>";
        return l
    },
    getDecadeHTML:function(r){
        var s=this.ts2date(r.ts);
        var t=parseInt(s.getFullYear());
        var l=[t-5,t+5];
        var p=this.mktime(s.getFullYear(),0,1);
        var n=r.m_ts;
        var o=this.mktime(t-12,0,1);
        var u=this.mktime(t+12,0,1);
        var m='<table class="year" cellpadding="0" summary="{';
        m+="'ts': '"+s.getTs()+"', 'pr_ts': '"+o+"', 'nx_ts': '"+u+"', 'label': '";
        m+=(l[0])+" - "+(l[1]+1)+"', 'current': 'decade'";
        m+='}">';
        var q=l[0];
        for(i=0;i<3;i++){
            m+="<tr>";
            for(y=0;y<4;y++){
                i_ts=this.mktime(q,0,1);
                i_date=this.mkdate(q,0,1);
                m+='<td ts="'+i_ts+'" m_ts="'+n+'" class="'+(r.parent&&p==i_ts?"selected":"")+(r.pickedDate.getFullYear()==i_date.getFullYear()?"current":"")+'">'+q+"</td>";
                q++
            }
            m+="</tr>"
        }
        m+="</table>";
        return m
    },
    setDate:function(b){
        this.pick({
            day:b.getDate(),
            month:b.getMonthNo(),
            year:b.getFullYear()
        })
    },
    daysInMonth:function(d,c){
        return 32-new Date(d,c,32).getDate()
    },
    mkdate:function(h,g,f){
        var e=new Date();
        if($defined(h)){
            e.setYear(h)
        }
        if($defined(g)){
            e.setMonth(g)
        }
        if($defined(f)){
            e.setDate(f)
        }
        e.setHours(0);
        e.setMinutes(0);
        e.setSeconds(0);
        e.setMilliseconds(0);
        return e
    },
    ts2date:function(c){
        var d=new Date();
        d.setTime(parseInt(c)*1000);
        return d
    },
    mktime:function(h,g,f){
        var e=this.mkdate(h,g,f);
        return e.getTs()
    }
});