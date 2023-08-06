<aside class="main-sidebar">

	 <section class="sidebar">

		<ul class="sidebar-menu">

		<?php

		if($_SESSION["perfil"] == "Administrador"){

			echo '<li class="active">

				<a href="inicio">

					<i class="fa fa-home"></i>
					<span>Inicio</span>

				</a>

			</li>

			<li>

				<a href="usuarios">

					<i class="fa fa-user"></i>
					<span>Usuarios</span>

				</a>

			</li>';

		}

		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Especial"){

			echo '<li>

				<a href="categorias">

					<i class="fa fa-th"></i>
					<span>Categorías</span>

				</a>

			</li>

			<li>

				<a href="subcategorias">

					<i class="fa fa-th"></i>
					<span>Subcategorías</span>

				</a>

			</li>

			<li>

				<a href="colores">

					<i class="fa fa-paint-brush"></i>
					<span>Colores</span>

				</a>

			</li>

			<li>

				<a href="tallas">

					<i class="fa fa-th"></i>
					<span>Tallas</span>

				</a>

			</li>

			<li>
				<a href="productos">
					<i class="fa fa-product-hunt"></i>
					<span>Productos</span>

				</a>

			</li>
			';

		}
		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){
			echo '<li class="treeview">
				<a href="#">
					<i class="fa fa-archive" aria-hidden="true"></i>
					<span>Inventario</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="movimientos">
							<i class="fa fa-circle-o"></i>
							<span>Inventario</span>
						</a>
					</li>
					<li>
						<a href="motivos">
							<i class="fa fa-circle-o"></i>
							<span>Motivos</span>
						</a>

					</li>';
				echo '</ul>
			</li>';
		}
		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){

			echo '<li>

				<a href="clientes">

					<i class="fa fa-users"></i>
					<span>Clientes</span>

				</a>

			</li>';

		}

		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){
			echo '<li class="treeview">
				<a href="#">
					<i class="fa fa-list-ul"></i>
					<span>Ventas</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="ventas">
							<i class="fa fa-circle-o"></i>
							<span>Administrar ventas</span>
						</a>
					</li>
					<li>
						<a href="crear-venta">
							<i class="fa fa-circle-o"></i>
							<span>Crear venta</span>
						</a>

					</li>';
				echo '</ul>
			</li>';
		}
		if($_SESSION["perfil"] == "Administrador"){

			echo '<li class="treeview">

				<a href="#">

					<i class="fa fa-line-chart"></i>
					
					<span>Reportes</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">';
					echo '<li>
						<a href="reportes">
							<i class="fa fa-circle-o"></i>
							<span>Reporte de ventas</span>
						</a>
					</li>';
				echo '</ul>

			</li>
			<li>
			<!-- <a href="acerca">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				<span>Acerca de</span>
			</a> -->

		</li>';

		}

		?>

		</ul>

	 </section>

</aside>