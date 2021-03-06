var SortingTable=new Class({
    Implements:Options,
    options:{
        zebra:true,
        details:false,
        paginator:false,
        dont_sort_class:"sortnone",
        forward_sort_class:"sortup",
        reverse_sort_class:"sortdown",
        header_clickable_class:"sortheader"
    },
    initialize:function(c,d){
        this.table=$(c);
        this.setOptions(d);
        this.tbody=this.table.getElement("tbody");
        if(this.options.zebra){
            SortingTable.stripe_table(this.tbody.getChildren())
        }
        this.headers=this.table.getElement("thead").getElements("th");
        this.headers.each(function(a,b){
            if(a.hasClass(this.options.dont_sort_class)){
                return
            }
            a.addClass(this.options.header_clickable_class);
            a.store("column",b);
            a.addEvent("mousedown",function(f){
                this.sort_by_header(f.target);
                if(this.options.paginator){
                    this.options.paginator.to_page(1)
                }
            }.bind(this))
        },this);
        this.load_conversions()
    },
    sort_by_header:function(h){
        var i=[];
        var j=this.tbody.getPrevious();
        this.tbody.dispose();
        var g=this.tbody.getChildren();
        while(row=g.shift()){
            row={
                row:row.dispose()
            };

            if(this.options.details){
                row.detail=g.shift().dispose()
            }
            i.unshift(row)
        }
        if(this.sort_column>=0&&this.sort_column==h.retrieve("column")){
            if(h.hasClass(this.options.reverse_sort_class)){
                h.removeClass(this.options.reverse_sort_class);
                h.addClass(this.options.forward_sort_class)
            }else{
                h.removeClass(this.options.forward_sort_class);
                h.addClass(this.options.reverse_sort_class)
            }
        }else{
            this.headers.each(function(a){
                a.removeClass(this.options.forward_sort_class);
                a.removeClass(this.options.reverse_sort_class)
            },this);
            this.sort_column=h.retrieve("column");
            if(h.retrieve("conversion_function")){
                this.conversion_matcher=h.retrieve("conversion_matcher");
                this.conversion_function=h.retrieve("conversion_function")
            }else{
                this.conversion_function=false;
                i.some(function(a){
                    var b=$(a.row.getElementsByTagName("td")[this.sort_column]).get("text");
                    if(b==""){
                        return false
                    }
                    this.conversions.some(function(c){
                        if(c.matcher.test(b)){
                            this.conversion_matcher=c.matcher;
                            this.conversion_function=c.conversion_function;
                            return true
                        }
                        return false
                    },this);
                    return !!(this.conversion_function)
                },this);
                h.store("conversion_function",this.conversion_function);
                h.store("conversion_matcher",this.conversion_matcher)
            }
            h.addClass(this.options.forward_sort_class);
            i.each(function(a){
                var b=this.conversion_function(a);
                a.toString=function(){
                    return b
                }
            },this);
            i.sort()
        }
        var f=0;
        while(row=i.shift()){
            this.tbody.appendChild(row.row);
            if(row.detail){
                this.tbody.appendChild(row.detail)
            }
            if(this.options.zebra){
                row.row.className=row.row.className.replace(this.removeAltClassRe,"$1").clean();
                if(row.detail){
                    row.detail.className=row.detail.className.replace(this.removeAltClassRe,"$1").clean()
                }
                if(f%2){
                    row.row.addClass("alt");
                    if(row.detail){
                        row.detail.addClass("alt")
                    }
                }
            }
            f++
        }
        this.tbody.inject(j,"after")
    },
    load_conversions:function(){
        this.conversions=$A([{
            matcher:/([0-9.]{1,8}).*([KMGT]{1})B/,
            conversion_function:function(h){
                var f=$(h.row.getElementsByTagName("td")[this.sort_column]).get("text");
                f=this.conversion_matcher.exec(f);
                if(!f){
                    return"0"
                }
                if(f[2]=="M"){
                    sort_val="1"
                }else{
                    if(f[2]=="G"){
                        sort_val="2"
                    }else{
                        if(f[2]=="T"){
                            sort_val="3"
                        }else{
                            sort_val="0"
                        }
                    }
                }
                var e=f[1].indexOf(".");
                if(e==-1){
                    post="00"
                }else{
                    var g=f[1].split(".");
                    f[1]=g[0];
                    post=g[1].concat("00".substr(0,2-g[1].length))
                }
                return sort_val.concat("00000000".substr(0,2-f[1].length).concat(f[1])).concat(post)
            }
        },{
            matcher:/(\d{1,2}) (.{3,6}) ago/,
            conversion_function:function(f){
                var e=$(f.row.getElementsByTagName("td")[this.sort_column]).get("text");
                e=this.conversion_matcher.exec(e);
                if(!e){
                    return"0"
                }
                var d;
                if(e[2].indexOf("month")!=-1){
                    d="1"
                }else{
                    if(e[2].indexOf("year")!=-1){
                        d="2"
                    }else{
                        d="0"
                    }
                }
                return d.concat("00".substr(0,2-e[1].length).concat(e[1]))
            }
        },{
            matcher:/((\d{1}\.\d{2}|\d{2}\.\d{2}|\d{3}\.\d{2}|\d{4}\.\d{2}|\d{5}\.\d{2}|\d{6}\.\d{2}))/,
            conversion_function:function(c){
                var d=$(c.row.getElementsByTagName("td")[this.sort_column]).get("text");
                d=d.replace(/[^\d]/g,"");
                return"00000000000000000000000000000000".substr(0,32-d.length).concat(d)
            }
        },{
            matcher:/(\d{4})-(\d{1,2})-(\d{1,2})/,
            conversion_function:function(c){
                var d=$(c.row.getElementsByTagName("td")[this.sort_column]).get("text");
                d=this.conversion_matcher.exec(d);
                return d[1]+"00".substr(0,2-d[2].length).concat(d[2])+"00".substr(0,2-d[3].length).concat(d[3])
            }
        },{
            matcher:/^\d+$/,
            conversion_function:function(c){
                var d=$(c.row.getElementsByTagName("td")[this.sort_column]).get("text");
                return"00000000000000000000000000000000".substr(0,32-d.length).concat(d)
            }
        },{
            matcher:/.*/,
            conversion_function:function(b){
                return $(b.row.getElementsByTagName("td")[this.sort_column]).get("text")
            }
        }])
    }
});
SortingTable.removeAltClassRe=new RegExp("(^|\\s)alt(?:\\s|$)");
SortingTable.implement({
    removeAltClassRe:SortingTable.removeAltClassRe
});
SortingTable.stripe_table=function(c){
    var d=0;
    c.each(function(a){
        if(!a.hasClass("collapsed")){
            d++
        }
        a.className=a.className.replace(this.removeAltClassRe,"$1").clean();
        if(d%2){
            a.addClass("alt")
        }
    })
};