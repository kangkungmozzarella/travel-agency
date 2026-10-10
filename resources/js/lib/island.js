import {
    BoxGeometry,
    CatmullRomCurve3,
    ConeGeometry,
    CylinderGeometry,
    DirectionalLight,
    EdgesGeometry,
    Group,
    HemisphereLight,
    IcosahedronGeometry,
    InstancedMesh,
    LineBasicMaterial,
    LineSegments,
    Mesh,
    MeshBasicMaterial,
    MeshStandardMaterial,
    Object3D,
    PCFSoftShadowMap,
    PerspectiveCamera,
    PlaneGeometry,
    Scene,
    SphereGeometry,
    SRGBColorSpace,
    TextureLoader,
    TorusGeometry,
    Vector3,
    WebGLRenderer,
} from 'three'

const ink = 0x173e32
const cream = 0xf8f5ed
const accent = 0xb34522
const sea = 0x4d8c80
const sand = 0xe6d7b8
const leaf = 0x2d6447
const leafLight = 0x5a9363
const islets = [-0.55, 0.95, 2.35, 3.85]

function random(seed) {
    return () => {
        seed = (seed + 0x6d2b79f5) | 0
        let value = Math.imul(seed ^ (seed >>> 15), 1 | seed)
        value = (value + Math.imul(value ^ (value >>> 7), 61 | value)) ^ value
        return ((value ^ (value >>> 14)) >>> 0) / 4294967296
    }
}

// Shared vertices on a geometry seam must move together, so offsets are keyed by position.
function roughen(geometry, amount, next) {
    const position = geometry.attributes.position
    const offsets = new Map()
    geometry.computeBoundingBox()
    const top = geometry.boundingBox.max.y
    for (let index = 0; index < position.count; index++) {
        const y = position.getY(index)
        if (y >= top - 0.001) continue
        const key = `${position.getX(index).toFixed(3)}:${y.toFixed(3)}:${position.getZ(index).toFixed(3)}`
        if (!offsets.has(key)) offsets.set(key, [next() - 0.5, next() - 0.5, next() - 0.5])
        const [x, dy, z] = offsets.get(key)
        position.setXYZ(
            index,
            position.getX(index) + x * amount,
            y + dy * amount * 0.6,
            position.getZ(index) + z * amount,
        )
    }
    geometry.computeVertexNormals()
    return geometry
}

export function createIsland(canvas, { destinations = [], image, onChange }) {
    const renderer = new WebGLRenderer({
        canvas,
        antialias: true,
        alpha: true,
        powerPreference: 'low-power',
    })
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
    renderer.shadowMap.enabled = true
    renderer.shadowMap.type = PCFSoftShadowMap

    const next = random(17)
    const scene = new Scene()
    const camera = new PerspectiveCamera(34, 1, 0.1, 100)
    const focus = new Vector3(0, 0.9, 0)
    const outline = new LineBasicMaterial({ color: ink })
    const paint = (color) => new MeshStandardMaterial({ color, flatShading: true, roughness: 0.92 })
    const solid = (geometry, color, edges = true) => {
        const mesh = new Mesh(geometry, typeof color === 'number' ? paint(color) : color)
        mesh.castShadow = true
        mesh.receiveShadow = true
        if (edges) mesh.add(new LineSegments(new EdgesGeometry(geometry, 28), outline))
        return mesh
    }

    scene.add(new HemisphereLight(cream, ink, 1.5))
    const sun = new DirectionalLight(0xffffff, 2.4)
    sun.position.set(6, 11, 5)
    sun.castShadow = true
    sun.shadow.mapSize.set(1024, 1024)
    Object.assign(sun.shadow.camera, { left: -7, right: 7, top: 7, bottom: -7, near: 1, far: 30 })
    scene.add(sun)

    // Sea slab with a hard offset plinth, the 3D version of the neobrutalist shadow.
    const slabGeometry = new CylinderGeometry(5.4, 5.4, 0.7, 72)
    const slab = solid(slabGeometry, [paint(ink), paint(sea), paint(ink)])
    slab.position.y = -0.35
    scene.add(slab)
    const plinth = new Mesh(slabGeometry, new MeshBasicMaterial({ color: ink }))
    plinth.position.set(0.32, -0.62, 0.32)
    scene.add(plinth)

    const island = new Group()
    scene.add(island)
    const beach = solid(roughen(new CylinderGeometry(2.6, 3.05, 0.5, 9), 0.25, next), sand)
    beach.position.y = 0.2
    const hill = solid(roughen(new CylinderGeometry(2.05, 2.45, 0.4, 9), 0.25, next), leafLight)
    hill.position.y = 0.6
    const mountain = solid(roughen(new ConeGeometry(1.5, 2.5, 7, 3), 0.35, next), leaf)
    mountain.position.set(-0.25, 2, -0.2)
    const cap = solid(new ConeGeometry(0.46, 0.7, 7), cream)
    cap.position.set(-0.25, 2.92, -0.2)
    island.add(beach, hill, mountain, cap)

    const trunkGeometry = new CylinderGeometry(0.035, 0.05, 0.3, 5)
    const crownGeometry = new ConeGeometry(0.22, 0.62, 5)
    const tree = (x, z, y, scale = 1) => {
        const group = new Group()
        const trunk = solid(trunkGeometry, ink, false)
        trunk.position.y = 0.15
        const crown = solid(crownGeometry, next() > 0.5 ? leaf : leafLight, false)
        crown.position.y = 0.55
        group.add(trunk, crown)
        group.position.set(x, y, z)
        group.scale.setScalar(scale * (0.8 + next() * 0.45))
        group.rotation.y = next() * Math.PI
        return group
    }
    for (let index = 0; index < 15; index++) {
        const angle = (index / 15) * Math.PI * 2 + next() * 0.3
        const radius = 1.45 + next() * 0.5
        island.add(tree(Math.cos(angle) * radius, Math.sin(angle) * radius, 0.78))
    }

    const pins = []
    const stops = []
    const isletGeometry = new CylinderGeometry(0.55, 0.72, 0.32, 7)
    islets.forEach((angle, index) => {
        const x = Math.cos(angle) * 4.1
        const z = Math.sin(angle) * 4.1
        const islet = solid(isletGeometry, sand)
        islet.position.set(x, 0.12, z)
        islet.rotation.y = next() * Math.PI
        scene.add(islet, tree(x - 0.2, z + 0.15, 0.28, 0.8))
        stops.push(new Vector3(x, 0.55, z))
        if (index >= destinations.length) return
        const pin = new Group()
        const stick = solid(new CylinderGeometry(0.03, 0.03, 0.95, 6), ink, false)
        stick.position.y = 0.48
        const head = solid(new SphereGeometry(0.17, 12, 8), accent)
        head.position.y = 1.05
        pin.add(stick, head)
        pin.position.set(x + 0.15, 0.28, z - 0.1)
        pin.userData.phase = index * 1.3
        scene.add(pin)
        pins.push({ pin, head })
    })

    // Route arcs over the sea between islets; dots read as a dashed line at any line width.
    const points = []
    stops.forEach((stop, index) => {
        const following = stops[(index + 1) % stops.length]
        points.push(stop, stop.clone().lerp(following, 0.5).setY(1.9))
    })
    const route = new CatmullRomCurve3(points, true, 'centripetal')
    const dotCount = 96
    const dots = new InstancedMesh(
        new SphereGeometry(0.04, 6, 4),
        new MeshBasicMaterial({ color: accent }),
        dotCount,
    )
    const placer = new Object3D()
    for (let index = 0; index < dotCount; index++) {
        placer.position.copy(route.getPointAt(index / dotCount))
        placer.updateMatrix()
        dots.setMatrixAt(index, placer.matrix)
    }
    scene.add(dots)

    const planeGeometry = new ConeGeometry(0.17, 0.55, 3)
    planeGeometry.rotateX(Math.PI / 2)
    planeGeometry.scale(1, 0.35, 1)
    const traveller = solid(planeGeometry, cream)
    scene.add(traveller)

    const ripples = [0, 1, 2].map(() => {
        const ring = new Mesh(
            new TorusGeometry(1, 0.025, 4, 72),
            new MeshBasicMaterial({ color: cream, transparent: true }),
        )
        ring.rotation.x = Math.PI / 2
        ring.position.y = 0.02
        scene.add(ring)
        return ring
    })

    const clouds = [0, 1, 2].map((index) => {
        const cloud = new Group()
        for (let part = 0; part < 3; part++) {
            const puff = solid(new IcosahedronGeometry(0.5 - part * 0.08, 1), cream, false)
            puff.position.set(part * 0.45 - 0.4, part === 1 ? 0.12 : 0, next() * 0.2)
            cloud.add(puff)
        }
        cloud.userData = {
            angle: index * 2.1,
            height: 3.4 + index * 0.45,
            radius: 3.2 + index * 0.7,
        }
        scene.add(cloud)
        return cloud
    })

    const postcard = new Group()
    const frame = solid(new BoxGeometry(2.3, 2.75, 0.06), cream)
    const backing = new Mesh(
        new BoxGeometry(2.3, 2.75, 0.06),
        new MeshBasicMaterial({ color: ink }),
    )
    backing.position.set(0.14, -0.14, -0.08)
    const photoMaterial = new MeshBasicMaterial({ color: 0xffffff })
    const photo = new Mesh(new PlaneGeometry(2.0, 2.15), photoMaterial)
    photo.position.set(0, 0.17, 0.035)
    const stripe = new Mesh(new PlaneGeometry(1.1, 0.07), new MeshBasicMaterial({ color: accent }))
    stripe.position.set(-0.35, -1.1, 0.035)
    postcard.add(backing, frame, photo, stripe)
    scene.add(postcard)
    const photoAspect = 2.0 / 2.15
    new TextureLoader().load(image, (texture) => {
        texture.colorSpace = SRGBColorSpace
        const aspect = texture.image.width / texture.image.height
        if (aspect > photoAspect) {
            texture.repeat.set(photoAspect / aspect, 1)
            texture.offset.set((1 - texture.repeat.x) / 2, 0)
        } else {
            texture.repeat.set(1, aspect / photoAspect)
            texture.offset.set(0, (1 - texture.repeat.y) / 2)
        }
        photoMaterial.map = texture
        photoMaterial.needsUpdate = true
        onChange?.()
    })

    let distance = 20
    const projected = new Vector3()
    const back = new Vector3()
    const right = new Vector3()

    function place(angle, lift) {
        camera.position.set(
            Math.sin(angle) * distance,
            distance * 0.62 + lift,
            Math.cos(angle) * distance,
        )
        camera.lookAt(focus)
    }

    return {
        resize(width, height) {
            renderer.setSize(width, height, false)
            camera.aspect = width / height
            distance = Math.min(30, Math.max(16.5, 18 / camera.aspect))
            camera.updateProjectionMatrix()
        },
        render(time, pointer) {
            place(0.62 + Math.sin(time * 0.13) * 0.32 + pointer.x * 0.28, pointer.y * -2.2)

            // Postcard keeps facing the viewer from the upper left, so it floats in front of the sea.
            back.subVectors(camera.position, focus).normalize()
            right.crossVectors(camera.up, back).normalize()
            postcard.position
                .copy(focus)
                .addScaledVector(right, -3.1)
                .addScaledVector(camera.up, 3.6 + Math.sin(time * 0.9) * 0.12)
                .addScaledVector(back, 2.2)
            postcard.quaternion.copy(camera.quaternion)
            postcard.rotateZ(0.1 + Math.sin(time * 0.6) * 0.03)
            postcard.rotateY(0.28 + Math.sin(time * 0.45) * 0.1)

            const travel = (time / 28) % 1
            traveller.position.copy(route.getPointAt(travel))
            traveller.lookAt(route.getPointAt((travel + 0.004) % 1))

            ripples.forEach((ring, index) => {
                const phase = (time * 0.18 + index / ripples.length) % 1
                ring.scale.setScalar(3.2 + phase * 1.9)
                ring.material.opacity = 0.75 * (1 - phase)
            })
            clouds.forEach((cloud) => {
                const { angle, height, radius } = cloud.userData
                const turn = angle + time * 0.04
                cloud.position.set(Math.cos(turn) * radius, height, Math.sin(turn) * radius)
                cloud.rotation.y = -turn
            })
            pins.forEach(({ pin, head }) => {
                head.position.y = 1.05 + Math.sin(time * 1.6 + pin.userData.phase) * 0.08
            })
            renderer.render(scene, camera)
        },
        anchors() {
            return pins.map(({ head }) => {
                head.getWorldPosition(projected)
                projected.y += 0.3
                projected.project(camera)
                return { x: (projected.x + 1) / 2, y: (1 - projected.y) / 2 }
            })
        },
        dispose() {
            scene.traverse((object) => {
                object.geometry?.dispose()
                for (const material of [object.material].flat()) {
                    material?.map?.dispose()
                    material?.dispose()
                }
            })
            renderer.dispose()
        },
    }
}
