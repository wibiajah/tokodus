<x-app-layout>
    <x-slot name="title">{{ $product->name }} - 3D Viewer</x-slot>

    <div style="display: flex; height: calc(100vh - 80px); margin-top: 80px; gap: 20px; padding: 20px; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);">
        <!-- Canvas 3D -->
        <div id="canvas-3d" style="flex: 1; background: white; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); overflow: hidden; position: relative;">
            <div id="loading-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; z-index: 10;">
                <p style="font-size: 16px; color: #999;">Loading 3D Box...</p>
            </div>
        </div>

        <!-- Control Panel -->
        <div style="width: 320px; background: white; padding: 24px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); overflow-y: auto; max-height: calc(100vh - 120px);">
            <h2 style="font-size: 22px; margin-bottom: 20px; color: #333;">{{ $product->name }}</h2>

            <div style="background: linear-gradient(135deg, #f9ef21 0%, #f0e800 100%); padding: 16px; border-radius: 8px; margin-bottom: 20px; color: #333; font-size: 13px;">
                <p style="margin: 4px 0;"><strong>📏 Dimensi</strong></p>
                <p style="margin: 4px 0;">{{ $product->width }}cm × {{ $product->height }}cm × {{ $product->depth }}cm</p>
                @if($product->price)
                    <p style="margin-top: 8px;"><strong>💰 Harga</strong></p>
                    <p style="margin: 4px 0;">Rp {{ number_format($product->price) }}</p>
                @endif
                @if($product->min_order)
                    <p style="margin-top: 8px;"><strong>📦 Min. Order</strong></p>
                    <p style="margin: 4px 0;">{{ $product->min_order }} unit</p>
                @endif
            </div>

            <!-- Controls -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #333;">🎨 Warna</label>
                <input type="color" id="colorPicker" value="{{ $product->default_color }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #333;">📋 Material</label>
                <select id="materialSelect" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; cursor: pointer;">
                    <option value="cardboard" {{ $product->material === 'cardboard' ? 'selected' : '' }}>Cardboard</option>
                    <option value="kraft" {{ $product->material === 'kraft' ? 'selected' : '' }}>Kraft</option>
                    <option value="glossy" {{ $product->material === 'glossy' ? 'selected' : '' }}>Glossy</option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #333;">
                    <input type="checkbox" id="autoRotate" checked style="cursor: pointer;"> 🔄 Rotasi Otomatis
                </label>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #333;">💡 Intensitas Cahaya</label>
                <input type="range" id="lightingSlider" min="0.3" max="2" step="0.1" value="1" style="width: 100%; cursor: pointer;">
            </div>

            <!-- Buttons -->
            <button onclick="downloadScreenshot()" style="width: 100%; padding: 12px; margin-top: 10px; background: linear-gradient(135deg, #f9ef21 0%, #f0e800 100%); color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">📸 Download Gambar</button>
            <button onclick="resetView()" style="width: 100%; padding: 12px; margin-top: 10px; background: #333; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s;">🔄 Reset View</button>
            <a href="{{ route('products-3d.index') }}" style="display: block; width: 100%; padding: 12px; margin-top: 10px; background: #ddd; color: #333; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; text-decoration: none; transition: all 0.3s;">← Kembali</a>
        </div>
    </div>

    <!-- Load THREE.js dari CDN yang benar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    
    <script>
        console.log('Script loaded');
        
        // Implementasi OrbitControls manual (karena tidak ada di CDN untuk r128)
        function setupOrbitControls() {
            THREE.OrbitControls = function(camera, domElement) {
                this.camera = camera;
                this.domElement = domElement;
                this.enabled = true;
                this.autoRotate = true;
                this.autoRotateSpeed = 3.0;
                this.enableDamping = true;
                this.dampingFactor = 0.05;
                this.enableZoom = true;
                this.minDistance = 5;
                this.maxDistance = 50;

                let spherical = new THREE.Spherical();
                let sphericalDelta = new THREE.Spherical();
                let scale = 1;
                let panOffset = new THREE.Vector3();
                let zoomChanged = false;

                let rotateStart = new THREE.Vector2();
                let rotateEnd = new THREE.Vector2();
                let rotateDelta = new THREE.Vector2();

                let panStart = new THREE.Vector2();
                let panEnd = new THREE.Vector2();
                let panDelta = new THREE.Vector2();

                let state = -1;
                const STATE = { NONE: -1, ROTATE: 0, DOLLY: 1, PAN: 2 };

                const EPS = 0.000001;
                const target = new THREE.Vector3();

                this.reset = () => {
                    target.set(0, 0, 0);
                    this.camera.position.set(15, 12, 15);
                    this.update();
                };

                this.update = (() => {
                    const offset = new THREE.Vector3();
                    const quat = new THREE.Quaternion().setFromUnitVectors(camera.up, new THREE.Vector3(0, 1, 0));
                    const quatInverse = quat.clone().invert();
                    const lastPosition = new THREE.Vector3();
                    const lastQuaternion = new THREE.Quaternion();

                    return function update() {
                        const position = this.camera.position;

                        offset.copy(position).sub(target);
                        offset.applyQuaternion(quat);
                        spherical.setFromVector3(offset);

                        if (this.autoRotate && state === STATE.NONE) {
                            sphericalDelta.theta -= 2 * Math.PI / 60 / 60 * this.autoRotateSpeed;
                        }

                        spherical.theta += sphericalDelta.theta;
                        spherical.phi += sphericalDelta.phi;
                        spherical.theta = Math.max(EPS, Math.min(Math.PI - EPS, spherical.theta));
                        spherical.makeSafe();
                        spherical.radius *= scale;
                        spherical.radius = Math.max(this.minDistance, Math.min(this.maxDistance, spherical.radius));

                        target.add(panOffset);

                        offset.setFromSpherical(spherical);
                        offset.applyQuaternion(quatInverse);
                        position.copy(target).add(offset);
                        this.camera.lookAt(target);

                        if (this.enableDamping === true) {
                            sphericalDelta.theta *= (1 - this.dampingFactor);
                            sphericalDelta.phi *= (1 - this.dampingFactor);
                            panOffset.multiplyScalar(1 - this.dampingFactor);
                        } else {
                            sphericalDelta.set(0, 0, 0);
                            panOffset.set(0, 0, 0);
                        }

                        scale = 1;

                        if (zoomChanged ||
                            lastPosition.distanceToSquared(this.camera.position) > EPS ||
                            8 * (1 - lastQuaternion.dot(this.camera.quaternion)) > EPS) {
                            lastPosition.copy(this.camera.position);
                            lastQuaternion.copy(this.camera.quaternion);
                            zoomChanged = false;
                            return true;
                        }
                        return false;
                    };
                })();

                // Mouse event handlers
                const onMouseDown = (event) => {
                    if (!this.enabled) return;
                    event.preventDefault();

                    if (event.button === 0) {
                        state = STATE.ROTATE;
                        rotateStart.set(event.clientX, event.clientY);
                    } else if (event.button === 2) {
                        state = STATE.PAN;
                        panStart.set(event.clientX, event.clientY);
                    }

                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                };

                const onMouseMove = (event) => {
                    if (!this.enabled) return;
                    event.preventDefault();

                    if (state === STATE.ROTATE) {
                        rotateEnd.set(event.clientX, event.clientY);
                        rotateDelta.subVectors(rotateEnd, rotateStart).multiplyScalar(0.005);
                        sphericalDelta.theta -= 2 * Math.PI * rotateDelta.x / this.domElement.clientHeight;
                        sphericalDelta.phi -= 2 * Math.PI * rotateDelta.y / this.domElement.clientHeight;
                        rotateStart.copy(rotateEnd);
                    } else if (state === STATE.PAN) {
                        panEnd.set(event.clientX, event.clientY);
                        panDelta.subVectors(panEnd, panStart).multiplyScalar(0.01);
                        panOffset.x -= panDelta.x;
                        panOffset.y += panDelta.y;
                        panStart.copy(panEnd);
                    }
                };

                const onMouseUp = () => {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                    state = STATE.NONE;
                };

                const onMouseWheel = (event) => {
                    if (!this.enabled || !this.enableZoom) return;
                    event.preventDefault();
                    if (event.deltaY < 0) {
                        scale /= 0.95;
                    } else if (event.deltaY > 0) {
                        scale *= 0.95;
                    }
                    zoomChanged = true;
                };

                this.domElement.addEventListener('mousedown', onMouseDown);
                this.domElement.addEventListener('wheel', onMouseWheel);
                this.domElement.addEventListener('contextmenu', (e) => e.preventDefault());
            };
        }

        // Tunggu THREE.js load
        function waitForThree(callback) {
            if (typeof THREE !== 'undefined') {
                setupOrbitControls();
                callback();
            } else {
                console.log('Waiting for THREE.js...');
                setTimeout(() => waitForThree(callback), 100);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM ready, waiting for THREE.js...');
            waitForThree(() => {
                console.log('THREE.js loaded, initializing 3D...');
                initThreeJS();
            });
        });

        function initThreeJS() {
            try {
                const container = document.getElementById('canvas-3d');
                console.log('Container:', container);

                if (!container) {
                    console.error('Container not found!');
                    return;
                }

                const scene = new THREE.Scene();
                scene.background = new THREE.Color(0xf0f0f0);

                const width = container.clientWidth;
                const height = container.clientHeight;
                console.log('Canvas size:', width, 'x', height);

                const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
                camera.position.set(15, 12, 15);
                camera.lookAt(0, 0, 0);

                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                renderer.setSize(width, height);
                renderer.shadowMap.enabled = true;
                renderer.shadowMap.type = THREE.PCFSoftShadowMap;
                
                container.innerHTML = '';
                container.appendChild(renderer.domElement);
                console.log('Renderer added to DOM');

                // Lighting
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
                scene.add(ambientLight);

                const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
                directionalLight.position.set(10, 20, 10);
                directionalLight.castShadow = true;
                directionalLight.shadow.mapSize.width = 2048;
                directionalLight.shadow.mapSize.height = 2048;
                scene.add(directionalLight);

                const pointLight = new THREE.PointLight(0xffffff, 0.4);
                pointLight.position.set(-10, 10, -10);
                scene.add(pointLight);
                console.log('Lights added');

                // Orbit Controls
                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                console.log('Controls initialized');

                // Create Box
                let box;

                function createBox(width, height, depth, color, material) {
                    console.log('Creating box:', width, height, depth, color, material);
                    
                    if (box) scene.remove(box);

                    const geometry = new THREE.BoxGeometry(width, height, depth);

                    let boxMaterial;
                    switch(material) {
                        case 'kraft':
                            boxMaterial = new THREE.MeshPhongMaterial({ 
                                color: color, 
                                shininess: 10,
                                side: THREE.DoubleSide
                            });
                            break;
                        case 'glossy':
                            boxMaterial = new THREE.MeshPhongMaterial({ 
                                color: color, 
                                shininess: 100,
                                side: THREE.DoubleSide
                            });
                            break;
                        default:
                            boxMaterial = new THREE.MeshPhongMaterial({ 
                                color: color, 
                                shininess: 30,
                                side: THREE.DoubleSide
                            });
                    }

                    box = new THREE.Mesh(geometry, boxMaterial);
                    box.castShadow = true;
                    box.receiveShadow = true;

                    const edges = new THREE.EdgesGeometry(geometry);
                    const line = new THREE.LineSegments(edges, new THREE.LineBasicMaterial({ color: 0x333333, linewidth: 2 }));
                    box.add(line);

                    scene.add(box);
                    console.log('Box created and added to scene');
                }

                // Get product data
                const productId = {{ $product->id }};
                const dataUrl = `/products-3d/${productId}/data`;
                console.log('Fetching data from:', dataUrl);

                fetch(dataUrl)
                    .then(res => {
                        console.log('Response status:', res.status);
                        if (!res.ok) throw new Error('Failed to fetch product data');
                        return res.json();
                    })
                    .then(data => {
                        console.log('Product data:', data);
                        createBox(data.width, data.height, data.depth, data.default_color, data.material);
                    })
                    .catch(err => {
                        console.error('Error fetching data:', err);
                        createBox({{ $product->width }}, {{ $product->height }}, {{ $product->depth }}, '{{ $product->default_color }}', '{{ $product->material }}');
                    });

                // Event Listeners
                document.getElementById('colorPicker').addEventListener('change', (e) => {
                    if (box && box.material) {
                        box.material.color.set(e.target.value);
                        console.log('Color changed to:', e.target.value);
                    }
                });

                document.getElementById('materialSelect').addEventListener('change', (e) => {
                    const color = document.getElementById('colorPicker').value;
                    createBox({{ $product->width }}, {{ $product->height }}, {{ $product->depth }}, color, e.target.value);
                });

                document.getElementById('autoRotate').addEventListener('change', (e) => {
                    controls.autoRotate = e.target.checked;
                    console.log('Auto rotate:', e.target.checked);
                });

                document.getElementById('lightingSlider').addEventListener('input', (e) => {
                    directionalLight.intensity = parseFloat(e.target.value);
                    console.log('Light intensity:', e.target.value);
                });

                // Animation Loop
                function animate() {
                    requestAnimationFrame(animate);
                    controls.update();
                    renderer.render(scene, camera);
                }
                animate();
                console.log('Animation loop started');

                // Functions
                window.downloadScreenshot = () => {
                    const link = document.createElement('a');
                    link.href = renderer.domElement.toDataURL('image/png');
                    link.download = '{{ $product->slug }}-3d-preview.png';
                    link.click();
                    console.log('Screenshot downloaded');
                };

                window.resetView = () => {
                    camera.position.set(15, 12, 15);
                    controls.reset();
                    console.log('View reset');
                };

                // Responsive
                window.addEventListener('resize', () => {
                    const newWidth = container.clientWidth;
                    const newHeight = container.clientHeight;
                    camera.aspect = newWidth / newHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(newWidth, newHeight);
                    console.log('Resized to:', newWidth, 'x', newHeight);
                });

            } catch (error) {
                console.error('Error initializing Three.js:', error);
                document.getElementById('canvas-3d').innerHTML = '<p style="padding: 20px; color: red;">Error: ' + error.message + '</p>';
            }
        }
    </script>
 
</x-app-layout>