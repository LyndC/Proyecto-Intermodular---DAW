# Hotel Reina Cristina - Sistema de Gestión de Reservas 🏨

Este es un proyecto intermodular para el ciclo de **Desarrollo de Aplicaciones Web (DAW)**. Se trata de una plataforma integral diseñada para la gestión automatizada de reservas de habitaciones, optimizando la operativa diaria del hotel y mejorando la experiencia del usuario final.

## 🚀 Tecnologías Aplicadas

El proyecto utiliza un stack tecnológico robusto y profesional:

* **Frontend:** HTML5 semántico, CSS3 personalizado y **Bootstrap 5** para un diseño *Responsive* (Mobile-First).
* **Backend:** **PHP** con arquitectura modular y lógica de control de sesiones.
* **Base de Datos:** **MySQL** gestionado a través de la interfaz **PDO** (PHP Data Objects) para garantizar seguridad contra inyección SQL mediante consultas preparadas.
* **Interactividad:** **JavaScript** para la gestión dinámica del calendario de disponibilidad.
* **Integraciones:** * **Stripe API** para la pasarela de pagos seguros.
    * **Formspree** para la gestión eficiente de formularios de contacto.

## 🛠️ Características Principales

- **Sistema de Reservas Dinámico:** Calendario interactivo que bloquea fechas ocupadas en tiempo real.
- **Control de Acceso por Roles:** Diferenciación de privilegios entre **Clientes**, **Staff** y **Administradores**.
- **Gestión CRUD:** Panel de administración para crear, leer, actualizar y dar de baja usuarios, habitaciones y reservas.
- **Seguridad:** Implementación de `session_start()` para la protección de rutas privadas y validación de datos en el servidor.
- **Diseño Profesional:** Interfaz limpia adaptada a la identidad corporativa del Hotel Reina Cristina.

## 📋 Metodología de Trabajo

Para el desarrollo de este proyecto se han aplicado estándares de la industria:

1.  **Metodología Ágil:** Gestión de tareas mediante un **Tablero Scrum** (Backlog, In Progress, Testing, Done).
2.  **Control de Versiones:** Uso estricto de **Git/GitHub** para el seguimiento del código y refactorización (migración exitosa de HTML estático a PHP dinámico).
3.  **Código Internacional:** Siguiendo las buenas prácticas, los comentarios técnicos del código fuente han sido redactados en **inglés** (*Standard English Documentation*).

## 🔧 Instalación y Configuración

1.  Clonar el repositorio:
    ```bash
    git clone [https://github.com/tu-usuario/Proyecto-Intermodular---DAW.git](https://github.com/tu-usuario/Proyecto-Intermodular---DAW.git)
    ```
2.  Importar el archivo SQL (solicitarlo de ser necesario).
3.  Configurar las credenciales de acceso en el archivo `conectar_db.php`.
4.  Asegurarse de tener instalado **Composer** si se requieren dependencias adicionales de Stripe.

## 📄 Licencia

Este proyecto fue desarrollado con fines educativos para el módulo intermodular de DAW.

![alt text](image.png)
© 2025 - Desarrollado por [Lynd]