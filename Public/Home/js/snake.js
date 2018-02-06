var map;		// 地图
	var snake;		// 蛇
	var code;		// 键盘keyCode
	var timing;		// 定时
	var operate;	// 操作界面
	var button;		// 按钮

	// 配置按钮
	var buttons = [{
		'html':  '开始',
		'color': 'green',
		'fun':   function()
		{
			timing = setInterval('snake.move()', 200);
		}
	},{
		'html':  '暂停',
		'color': 'red',
		'fun':   function()
		{
			clearInterval(timing);
		}
	},{
		'html': '上',
		'code': 38,
	},{
		'html': '左',
		'code': 37,
	},{
		'html': '右',
		'code': 39,
	},{
		'html': '下',
		'code': 40,
	}, {
		'html': '重新开始',
		'fun' : function()
		{
			for (i = 0; i< snake.body.length; i ++)
			{
				map._map.removeChild(snake.body[i][3]);
			}

			// 重新初始化
			snake.direction = 'right';		// 方向
			snake.body = [[3,2,'red', null],[2, 2, 'blue', null],[1, 2, 'blue', null]];
			snake.show();
			button._buts[7].value = 0;
		}
	},{
		'type':  'input',
		'value':  0,
		'width':   60,
		'height':  45,
		'background':'white',
		'color':'red'
	}];
	// 定义地图类
	function Map()
	{
		this.width    = 800;
		this.height   = 400;
		this.color    = '#dddddd';
		this.position = 'absolute';
		this._map     = null;
		this.show = function()
		{
			this._map = document.createElement('div');
			this._map.style.width = this.width + 'px';
			this._map.style.height = this.height + 'px';
			this._map.style.background = this.color;
			this._map.style.position = this.position;
			this._map.style.margin   = '0px  auto';
			document.getElementById('mycontent').appendChild(this._map);
		}
	}

	// 定义食物
	function Food()
	{
		this.width    = 20;
		this.height   = 20;
		this.x 		  = 0;
		this.y 	 	  = 0;
		this.color    = 'green';
		this.position = 'absolute';
		this._food    = null; 
		this.show = function()
		{
			if (this._food == null)
			{
				this._food = document.createElement('div');
				// 大小位置信息
				this._food.style.width  = this.width + 'px';
				this._food.style.height = this.height + 'px';
				// 位置背景信息
				this._food.style.position   = this.position;
				this._food.style.background = this.color;
				
				map._map.appendChild(this._food);
			}

			this.x = Math.floor(Math.random() * 40) ;
			this.y = Math.floor(Math.random() * 20) ;
			this._food.style.left   = this.x * 20 + 'px';
			this._food.style.top    = this.y * 20 + 'px';
		}
	}

	// 定义蛇
	function Snake()
	{
		this.width  = 20;
		this.height = 20;
		this.position  = 'absolute';
		this.direction = 'right';		// 方向
		this.body = [[3,2,'red', null],[2, 2, 'blue', null],[1, 2, 'blue', null]];
	
		this.show = function()
		{
			for (var i = 0; i < this.body.length; i++)
			{
				if (this.body[i][3] == null)
				{
					this.body[i][3] = document.createElement('div');
					this.body[i][3].style.width  = this.width + 'px';
					this.body[i][3].style.height = this.height + 'px';
					this.body[i][3].style.background = this.body[i][2];
					this.body[i][3].style.position = this.position;
					map._map.appendChild(this.body[i][3]);
				}

				this.body[i][3].style.left = this.body[i][0] * 20 + 'px';
				this.body[i][3].style.top  = this.body[i][1] * 20 + 'px';	
			}
		}

		// 设置方向
		this.setDirection = function(code)
		{
			// 控制方向时,在同一个方向和相反方向下点击无效(防止来回穿)
			switch (code)
			{
				case 37: 
					if (this.direction != 'right' && this.direction != 'left') this.direction = 'left'; 	
					break;
				case 38: 
					if (this.direction != 'down' && this.direction != 'up') this.direction = 'up'; 	
					break;
				case 39: 
					if (this.direction != 'left' && this.direction != 'right') this.direction = 'right'; 	
					break;
				case 40: 
					if (this.direction != 'up' && this.direction != 'down') this.direction = 'down'; 	
					break;
			}
		}

		// 移动
		this.move = function()
		{
			var mx = this.body[0][0], // 第一个的x轴坐标
				my = this.body[0][1]; // 第一个的y轴坐标
			// 判读越界
			if (mx < 0 || my < 0 || mx >= 40 || my >= 20)
			{
				clearInterval(timing);
				alert('你超出界限了');
				return false;
			}

			// 判断吃东西
			if (mx == food.x && my == food.y)
			{
				this.body.push([10, 10, 'blue', null]);
				// 食物被吃掉后重新显示
				food.show();
				button._buts[7].value ++;
			}

			for (var i = this.body.length - 1; i > 0; i--)
			{
				// 判断撞到自己
				if (mx == this.body[i][0] && my == this.body[i][1])
				{
					clearInterval(timing);
					alert('吃到自己了哦!');
					return false;
				}

				this.body[i][0] = this.body[i-1][0];
				this.body[i][1] = this.body[i-1][1];
			}

			// 根据方向前进
			switch (this.direction)
			{
				case 'right': this.body[0][0] ++; break;
				case 'left' : this.body[0][0] --; break;
				case 'up'   : this.body[0][1] --; break;
				case 'down' : this.body[0][1] ++; break; 
			}

			this.show();
		}
	}


	// 操作类
	function Operate()
	{
		this.width  = 800;
		this.height = 60;
		this.top    = 400;
		this.color  = 'orange';
		this.position = 'absolute';
		this._poerate = null;

		this.show = function()
		{
			this._poerate = document.createElement('div');
			this._poerate.style.width  = this.width + 'px';
			this._poerate.style.height = this.height + 'px';
			this._poerate.style.top    = this.top + 'px';
			this._poerate.style.background = this.color; 
			this._poerate.style.position = this.position;
			document.getElementById('mycontent').appendChild(this._poerate);
		}

	}

	function Button()
	{
		// 定义属性
		this.width  = 100;
		this.height = 50;
		this.top    = 5;
		this.left   = 0;
		this.html   = '开始';
		this.type   = 'button';
		this.posit  = 'relative';
		this.buttons = buttons;
		this._buts = [];

		this.show = function(obj)
		{

			for (var i in this.buttons)
			{
				this._buts.push(this.create(this.buttons[i]));
			}
		}

		// 创建按钮对象
		this.create = function(obj)
		{
			// 初始化话赋值
			if (obj.type   == undefined) obj.type   = this.type;
			if (obj.width  == undefined) obj.width  = this.width;
			if (obj.height == undefined) obj.height = this.height;
			if (obj.top    == undefined) obj.top    = this.top;
			if (obj.posit  == undefined) obj.posit  = this.posit;
			if (obj.fun    == undefined) obj.fun    = function()
			{
				snake.setDirection(obj.code);
			}
			
			// 创建对象
			var button = document.createElement(obj.type);
			button.style.width  = obj.width + 'px';
			button.style.height = obj.height + 'px';
			button.style.top    = obj.top + 'px';
			button.style.position = obj.posit;
			if (obj.color) button.style.color = obj.color;
			if (obj.value) button.value = obj.value;
			if (obj.html)  button.innerHTML = obj.html;
			if (obj.background) button.style.background = obj.background;
			button.onclick = obj.fun;
			operate._poerate.appendChild(button);
			return button;
		}
	}

	window.onload = function()
	{
		// 实例化地图
		map = new Map();
		map.show();

		// 实例化食物
		food = new Food();
		food.show();

		// 实例化蛇
		snake = new Snake();
		snake.show();

		// 实例化操作类
		operate = new Operate();
		operate.show();

		// 实例化按钮对象
		button = new Button();
		button.show();

		// 键盘事件
		document.onkeydown = function(event)
		{
			event = window.event || event;
			code = event.keyCode;
			snake.setDirection(code);
		}
	}