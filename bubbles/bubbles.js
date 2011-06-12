/*
 *  This prototype represents a canvas div containing balls of various sizes.
 *  canvas: the HTMLelement where the balls will be painted. 
 *  [cx]: OPTIONAL x coordinate of the center (default is half the width) 
 *  [cy]: OPTIONAL y coordinate of the center (default is half the height)
 */

function BubbleCanvas (canvas, cx, cy) {

  canvas.style['background-color'] = '#202020';
  //console.log( (cx!=null)?cx:parseInt(canvas.style.width)/2);
  var centerX = cx?cx:parseInt(canvas.style.width)/2;
  var centerY = cy?cy:parseInt(canvas.style.width)/2;
  var totalR = 0; //total radius (deprecated)
  var elements = [], bodies = [];
  var timeStep = 1.0/40;
  var iteration = 1;  

  //i create the box2D world
  var worldAABB = new b2AABB();
  worldAABB.minVertex.Set(-600, -600);
  worldAABB.maxVertex.Set(600, 600);
  var gravity = new b2Vec2(0, 0);//(0,300)
  var doSleep = true;
  var world = new b2World(worldAABB, gravity, doSleep); 
   //END: box2D world creation
  
  BubbleCanvas.prototype.addBubble = function addBubble(radius) {
    console.log("add bubble");
    var element = document.createElement("canvas");
	var x = Math.random() * 400;//centerX + totalR -radius;
	var y = Math.random() * 400;//centerY -radius;
	element.width = radius*2;
	element.height = radius*2;
	element.style['position'] = 'absolute';
	element.style['left'] = x + 'px';
	element.style['top'] = y + 'px';
    elements.push(element);

	var graphics = element.getContext("2d");
	//draw the circle:
	graphics.fillStyle = "#5EADED";
	graphics.beginPath();
	graphics.arc(radius, radius, radius, 0, Math.PI * 2, true); 
	graphics.closePath();
	graphics.fill();
	graphics.strokeStyle = "#1D7DAD";
	graphics.stroke();
	//end drawing
	canvas.appendChild(element);
	totalR += radius;

	//box2D adding a circle:
	var b2body = new b2BodyDef();

	var circle = new b2CircleDef();
	circle.radius = radius*2 >> 1;
	circle.density = 1;
	circle.friction = 0.5;
	circle.restitution = 0.0;
	b2body.AddShape(circle);
	b2body.userData = {element: element};

	b2body.position.Set( x, y );
	//b2body.linearVelocity.Set( Math.random() * 400 - 200, Math.random() * 400 - 200 );
	bodies.push( world.CreateBody(b2body) );
	//circle added.

  }

  BubbleCanvas.prototype.step = function step() {
    world.Step(timeStep, iteration);
	for (i = 0; i < bodies.length; i++) {
		var body = bodies[i];
		var element = elements[i];
		//apply a force towards the center
		body.WakeUp();
		force = new b2Vec2(200 - body.m_position0.x,200 - body.m_position0.y);
		
		//body.ApplyImpulse(force, body.m_position);
		body.SetLinearVelocity(force);
		element.style.left = (body.m_position0.x - (element.width >> 1)) + 'px';
		element.style.top = (body.m_position0.y - (element.height >> 1)) + 'px';
	}
  }


}
