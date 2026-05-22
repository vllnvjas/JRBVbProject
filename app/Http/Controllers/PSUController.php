<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PSUController extends Controller
{
    //
        public function welcome () {
            return "Welcome, Jasmin Rose B. Villanueva";
        }
        public function mission () {
        return "MISSION <br> <br>The Pangasinan State University shall provide<br> a human-centric, resilient, and sustainable<br>academic environment to produce dynamic,<br>
        responsive, and future-ready individuals<br> capable of meeting the requirements of the<br> local and global communities and industries.";
    }

    public function vision () {
        return "VISION <br> <br>To be a leading industry-driven State University in the ASEAN region by 2030.";
    }

    public function EOMSPolicy () {
        return "QUALITY PLICY <br> <br>The Pangasinan State university, shall be recognized as an ASEAN premier<br>state universities that provides quality education and satisfactory service<br> deliviry
        through instruction, reserach, extension, and production. <br> <br>
        We commit our rxperties and resources to produce professionals who meet<br> the expectations of the industry and other interested parties in the national<br>
        and international community. <br> <br>
        We shall continuously improve our operations in response to the changing<br>environment and in support of the instruction's strategic direction.";

    }

    public function student ($name, $course) {
        return "Student: $name <br> Course: $course";
    }
    

}
