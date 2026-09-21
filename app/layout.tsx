import "./globals.css";
import type { Metadata } from "next";
export const metadata: Metadata={title:"HavenStone Realty | Premium Real Estate",description:"Discover exceptional homes, apartments and land across Lagos with HavenStone Realty."};
export default function RootLayout({children}:{children:React.ReactNode}){return <html lang="en"><body>{children}</body></html>}