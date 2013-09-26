function Sound()
{
    this.element;
    this.currentTime;

    this.init=function()
    {
        this.element=document.createElement('audio');
    }
    this.start=function(source)
    {
        this.element.setAttribute('src', source);
        this.element.play();
    }
    this.pause=function()
    {
        this.currentTime = this.element.currentTime;
        this.element.pause();
    }
    this.play=function()
    {
        this.element.currentTime = this.currentTime;
        this.element.play();
    }
}
/*

var foo=new Sound("url",100,true);
foo.start();
foo.stop();
foo.start();
foo.init(100,false);
foo.remove();

 */