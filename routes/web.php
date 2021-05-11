<?php

Route::view("/{path?}", "layouts.index")->where('path', '.*');



