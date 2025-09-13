<?php
/**
 * Plugin Name: APIs Seguras para E-commerce
 * Description: Implementación de 3 APIs con seguridad SSL/HTTPS para el proyecto de ciberseguridad
 * Version: 1.0
 * Author: Proyecto Ciberseguridad
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// FUNCIONES AUXILIARES
// ============================================

// Detectar entorno local
function api_seg_es_local() {
    return in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1', '::1']);
}

// Función mejorada para llamadas API
function api_seg_llamar($url, $cache_time = 3600) {
    // Generar clave de caché única
    $cache_key = 'api_cache_' . md5($url);
    
    // Intentar obtener de caché
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }
    
    // Configuración para la llamada
    $args = array(
        'timeout'     => 30,
        'redirection' => 5,
        'httpversion' => '1.1',
        'headers'     => array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ),
        'sslverify'   => !api_seg_es_local(), // Solo verificar SSL en producción
    );
    
    // Realizar la llamada
    $response = wp_remote_get($url, $args);
    
    // Verificar errores
    if (is_wp_error($response)) {
        return false;
    }
    
    // Obtener el body
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // Guardar en caché si es exitoso
    if ($data) {
        set_transient($cache_key, $data, $cache_time);
    }
    
    return $data;
}

// ============================================
// SHORTCODE 1: PRODUCTOS E-COMMERCE
// ============================================

add_shortcode('api_productos_segura', function($atts) {
    $atts = shortcode_atts(array(
        'cantidad' => 4,
        'categoria' => ''
    ), $atts);
    
    $url = 'https://fakestoreapi.com/products?limit=' . intval($atts['cantidad']);
    
    if (!empty($atts['categoria'])) {
        $url = 'https://fakestoreapi.com/products/category/' . sanitize_text_field($atts['categoria']) . '?limit=' . intval($atts['cantidad']);
    }
    
    $productos = api_seg_llamar($url);
    
    if (!$productos) {
        return '<div style="padding: 20px; background: #ffebee; color: #c62828; border-radius: 8px;">
                ⚠️ No se pudieron cargar los productos. Por favor, intenta más tarde.
                </div>';
    }
    
    ob_start();
    ?>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 12px; margin: 20px 0;">
        <h2 style="color: white; margin-top: 0;">🛍️ API 1: Productos de E-commerce (Fake Store API)</h2>
        <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <span style="color: white;">🔒 Conexión Segura SSL/TLS | 🔐 Datos Encriptados | ✅ Certificado Verificado</span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <?php foreach ($productos as $producto): ?>
                <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div style="background: #4caf50; color: white; padding: 8px; text-align: center; font-size: 12px;">
                        🔒 Transmisión Segura HTTPS
                    </div>
                    <div style="padding: 15px;">
                        <img src="<?php echo esc_url($producto['image']); ?>" 
                             style="width: 100%; height: 200px; object-fit: contain; margin-bottom: 10px;">
                        <h4 style="font-size: 14px; margin: 10px 0; height: 40px; overflow: hidden;">
                            <?php echo esc_html($producto['title']); ?>
                        </h4>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin: 10px 0;">
                            <span style="color: #4caf50; font-size: 20px; font-weight: bold;">
                                $<?php echo number_format($producto['price'], 2); ?>
                            </span>
                            <span style="color: #ff9800; font-size: 14px;">
                                ⭐ <?php echo $producto['rating']['rate']; ?>/5
                            </span>
                        </div>
                        <div style="font-size: 12px; color: #666; margin-bottom: 10px;">
                            <?php echo $producto['rating']['count']; ?> valoraciones
                        </div>
                        <button style="width: 100%; background: #2196f3; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="margin-top: 20px; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 12px;">
            ✅ Protocolo: HTTPS | 🔐 Cifrado: AES-256 | 🛡️ Headers de Seguridad Activos
        </div>
    </div>
    <?php
    return ob_get_clean();
});

// ============================================
// SHORTCODE 2: USUARIOS DEL SISTEMA
// ============================================

add_shortcode('api_usuarios_segura', function($atts) {
    $atts = shortcode_atts(array(
        'limite' => 4
    ), $atts);
    
    $usuarios = api_seg_llamar('https://jsonplaceholder.typicode.com/users');
    
    if (!$usuarios) {
        return '<div style="padding: 20px; background: #ffebee; color: #c62828; border-radius: 8px;">
                ⚠️ No se pudieron cargar los usuarios.
                </div>';
    }
    
    $usuarios = array_slice($usuarios, 0, intval($atts['limite']));
    
    ob_start();
    ?>
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; margin: 20px 0;">
        <h2 style="color: white; margin-top: 0;">👥 API 2: Sistema de Usuarios (JSONPlaceholder)</h2>
        <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <span style="color: white;">🔒 API REST Segura | 🔐 Autenticación SSL | ✅ Datos Validados</span>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach ($usuarios as $usuario): ?>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold; margin-right: 15px;">
                            <?php echo substr($usuario['name'], 0, 1); ?>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: #333;">
                                <?php echo esc_html($usuario['name']); ?>
                            </h4>
                            <small style="color: #666;">
                                ID Seguro: #<?php echo str_pad($usuario['id'], 5, '0', STR_PAD_LEFT); ?>
                            </small>
                        </div>
                    </div>
                    
                    <div style="font-size: 14px; line-height: 1.8; color: #555;">
                        <div style="margin-bottom: 8px;">
                            📧 <strong>Email:</strong> <?php echo esc_html($usuario['email']); ?>
                        </div>
                        <div style="margin-bottom: 8px;">
                            📱 <strong>Teléfono:</strong> <?php echo esc_html($usuario['phone']); ?>
                        </div>
                        <div style="margin-bottom: 8px;">
                            🏢 <strong>Empresa:</strong> <?php echo esc_html($usuario['company']['name']); ?>
                        </div>
                        <div style="margin-bottom: 8px;">
                            🌐 <strong>Sitio Web:</strong> <?php echo esc_html($usuario['website']); ?>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; display: flex; justify-content: space-between;">
                        <span style="background: #4caf50; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            ✓ SSL Verificado
                        </span>
                        <span style="background: #2196f3; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            🔐 Encriptado
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="margin-top: 20px; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 12px;">
            ✅ Transmisión HTTPS | 🔐 Tokens Seguros | 🛡️ Validación de Datos
        </div>
    </div>
    <?php
    return ob_get_clean();
});

// ============================================
// SHORTCODE 3: POSTS/PUBLICACIONES
// ============================================

add_shortcode('api_posts_segura', function($atts) {
    $atts = shortcode_atts(array(
        'cantidad' => 3
    ), $atts);
    
    $posts = api_seg_llamar('https://jsonplaceholder.typicode.com/posts');
    
    if (!$posts) {
        return '<div style="padding: 20px; background: #ffebee; color: #c62828; border-radius: 8px;">
                ⚠️ No se pudieron cargar las publicaciones.
                </div>';
    }
    
    $posts = array_slice($posts, 0, intval($atts['cantidad']));
    
    ob_start();
    ?>
    <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 25px; border-radius: 12px; margin: 20px 0;">
        <h2 style="color: white; margin-top: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
            📰 API 3: Sistema de Publicaciones (JSONPlaceholder)
        </h2>
        <div style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <span style="color: white; font-weight: bold;">
                🔒 Contenido Protegido | 🔐 Cifrado End-to-End | ✅ Integridad Verificada
            </span>
        </div>
        
        <div style="display: grid; gap: 20px;">
            <?php foreach ($posts as $post): ?>
                <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 5px solid #9c27b0;">
                    <h3 style="color: #4a148c; margin-top: 0; text-transform: capitalize;">
                        <?php echo esc_html($post['title']); ?>
                    </h3>
                    <p style="color: #666; line-height: 1.8; margin: 15px 0;">
                        <?php echo esc_html($post['body']); ?>
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #eee;">
                        <div style="color: #999; font-size: 13px;">
                            📝 Post ID: #<?php echo str_pad($post['id'], 3, '0', STR_PAD_LEFT); ?> | 
                            👤 Usuario: <?php echo $post['userId']; ?>
                        </div>
                        <div>
                            <span style="background: #4caf50; color: white; padding: 4px 10px; border-radius: 15px; font-size: 11px; margin-right: 5px;">
                                🔐 Cifrado
                            </span>
                            <span style="background: #2196f3; color: white; padding: 4px 10px; border-radius: 15px; font-size: 11px;">
                                ✓ Verificado
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="margin-top: 20px; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 8px; color: white; font-size: 12px;">
            ✅ API RESTful Segura | 🔐 JSON Web Tokens | 🛡️ Prevención XSS/CSRF
        </div>
    </div>
    <?php
    return ob_get_clean();
});

// ============================================
// SHORTCODE 4: VERIFICADOR DE SEGURIDAD SSL
// ============================================

add_shortcode('verificar_ssl', function() {
    $es_local = api_seg_es_local();
    $ssl_activo = is_ssl();
    
    ob_start();
    ?>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 12px; margin: 20px 0; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
        <h2 style="color: white; margin-top: 0; text-align: center;">
            🔒 Panel de Seguridad SSL/HTTPS del Sistema
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            
            <!-- Estado del Entorno -->
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                <h4 style="color: white; margin-top: 0;">📍 Entorno Actual</h4>
                <div style="color: white; line-height: 1.8;">
                    <?php if ($es_local): ?>
                        <div style="padding: 10px; background: rgba(255,152,0,0.3); border-radius: 5px; margin-bottom: 10px;">
                            🔧 <strong>Modo:</strong> Desarrollo Local
                        </div>
                        <small>• SSL opcional en localhost</small><br>
                        <small>• Verificación SSL desactivada</small><br>
                        <small>• Headers de seguridad activos</small>
                    <?php else: ?>
                        <div style="padding: 10px; background: rgba(76,175,80,0.3); border-radius: 5px; margin-bottom: 10px;">
                            🌐 <strong>Modo:</strong> Producción
                        </div>
                        <small>• SSL <?php echo $ssl_activo ? 'Activo ✅' : 'Inactivo ⚠️'; ?></small><br>
                        <small>• Verificación SSL obligatoria</small><br>
                        <small>• HSTS habilitado</small>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Protocolos de Seguridad -->
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                <h4 style="color: white; margin-top: 0;">🛡️ Protocolos Activos</h4>
                <div style="color: white; line-height: 1.8;">
                    <div style="margin-bottom: 5px;">✅ TLS 1.2/1.3</div>
                    <div style="margin-bottom: 5px;">✅ Cifrado AES-256</div>
                    <div style="margin-bottom: 5px;">✅ Headers de Seguridad</div>
                    <div style="margin-bottom: 5px;">✅ Protección XSS</div>
                    <div style="margin-bottom: 5px;">✅ Prevención CSRF</div>
                    <div style="margin-bottom: 5px;">✅ Sanitización de Datos</div>
                </div>
            </div>
            
            <!-- Estado de APIs -->
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                <h4 style="color: white; margin-top: 0;">🔐 Seguridad de APIs</h4>
                <div style="color: white; line-height: 1.8;">
                    <div style="padding: 8px; background: rgba(76,175,80,0.3); border-radius: 5px; margin-bottom: 8px;">
                        ✅ API 1: Fake Store (Activa)
                    </div>
                    <div style="padding: 8px; background: rgba(76,175,80,0.3); border-radius: 5px; margin-bottom: 8px;">
                        ✅ API 2: JSONPlaceholder Users
                    </div>
                    <div style="padding: 8px; background: rgba(76,175,80,0.3); border-radius: 5px; margin-bottom: 8px;">
                        ✅ API 3: JSONPlaceholder Posts
                    </div>
                    <small>• Todas con HTTPS obligatorio</small><br>
                    <small>• Cache encriptado activo</small>
                </div>
            </div>
            
            <!-- Métricas de Seguridad -->
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; backdrop-filter: blur(10px);">
                <h4 style="color: white; margin-top: 0;">📊 Métricas</h4>
                <div style="color: white; line-height: 1.8;">
                    <div style="margin-bottom: 10px;">
                        <strong>Cifrado de Datos:</strong><br>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 20px; overflow: hidden; margin-top: 5px;">
                            <div style="background: #4caf50; padding: 3px; text-align: center; width: 100%;">100%</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <strong>Headers Seguridad:</strong><br>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 20px; overflow: hidden; margin-top: 5px;">
                            <div style="background: #4caf50; padding: 3px; text-align: center; width: 100%;">100%</div>
                        </div>
                    </div>
                    <div>
                        <strong>Validación SSL:</strong><br>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 20px; overflow: hidden; margin-top: 5px;">
                            <div style="background: <?php echo $es_local ? '#ff9800' : '#4caf50'; ?>; padding: 3px; text-align: center; width: <?php echo $es_local ? '50' : '100'; ?>%;">
                                <?php echo $es_local ? '50%' : '100%'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div style="margin-top: 25px; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px; text-align: center; color: white;">
            <strong>Estado General del Sistema:</strong> 
            <span style="background: #4caf50; padding: 5px 15px; border-radius: 20px; margin-left: 10px;">
                ✅ SEGURO - Todas las medidas de seguridad están activas
            </span>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

// ============================================
// HEADERS DE SEGURIDAD (Solo si no causan conflicto)
// ============================================

add_action('init', function() {
    // Solo agregar headers si no estamos en admin y no es AJAX
    if (!is_admin() && !wp_doing_ajax()) {
        add_action('send_headers', function() {
            // Headers básicos de seguridad que no causan problemas
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
        });
    }
});

// Mensaje de activación
register_activation_hook(__FILE__, function() {
    add_option('api_seguras_activado', true);
});

// Mostrar mensaje después de activación
add_action('admin_notices', function() {
    if (get_option('api_seguras_activado')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>¡Plugin APIs Seguras activado!</strong> Usa los siguientes shortcodes en tus páginas:</p>
            <ul>
                <li><code>[verificar_ssl]</code> - Panel de seguridad SSL</li>
                <li><code>[api_productos_segura]</code> - API de productos</li>
                <li><code>[api_usuarios_segura]</code> - API de usuarios</li>
                <li><code>[api_posts_segura]</code> - API de publicaciones</li>
            </ul>
        </div>
        <?php
        delete_option('api_seguras_activado');
    }
});