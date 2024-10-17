import { Canvas } from "@react-three/fiber";
import { useGLTF } from "@react-three/drei";
import { Environment, OrbitControls } from "@react-three/drei";
import React, { Suspense } from "react";

function Cook() {
  const gltf = useGLTF("/asset/pizza.glb");
  return (
    <div>
      <Canvas>
        <Suspense fallback={null}>
          <primitive object={gltf.scene} scale={5}/>
          <OrbitControls />
          <Environment preset="sunset" />
        </Suspense>
      </Canvas>
    </div>
  );
}

export default Cook;
