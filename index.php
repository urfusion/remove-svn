$path = $_SERVER['DOCUMENT_ROOT'].'/work/remove-svn-php/'; // path of your directory 
header( 'Content-type: text/plain' ); // plain text for easy display

// preconditon: $dir ends with a forward slash (/) and is a valid directory
// postcondition: $dir and all it's sub-directories are recursively
// searched through for .svn directories. If a .svn directory is found,
// it is deleted to remove any security holes. 
function removeSVN( $dir ) {
    //echo "Searching: $dirnt";

    $flag = false; // haven't found svn directory
    $svn = $dir . '.svn';

    if( is_dir( $svn ) ) {
        if( !chmod( $svn, 0777 ) )
            echo "File permissions could not be changed (this may or may not be a problem--check the statement below).nt"; // if the permissions were already 777, this is not a problem

        delTree( $svn ); // remove the .svn directory with a helper function

        if( is_dir( $svn ) ) // deleting failed
            echo "Failed to delete $svn due to file permissions.";
        else
            echo "Successfully deleted $svn from the file system.";

        $flag = true; // found directory
    }

    if( !$flag ) // no .svn directory
        echo 'No .svn directory found.';
    echo "nn";

    $handle = opendir( $dir );
    while( false !== ( $file = readdir( $handle ) ) ) {
        if( $file == '.' || $file == '..' ) // don't get lost by recursively going through the current or top directory
            continue;

        if( is_dir( $dir . $file ) )
            removeSVN( $dir . $file . '/' ); // apply the SVN removal for sub directories
    }
}

// precondition: $dir is a valid directory
// postcondition: $dir and all it's contents are removed
// simple function found at http://www.php.net/manual/en/function.rmdir.php#93836
function delTree( $dir ) {
    $files = glob( $dir . '*', GLOB_MARK ); // find all files in the directory

    foreach( $files as $file ) {
        if( substr( $file, -1 ) == '/')
            delTree( $file ); // recursively apply this to sub directories
        else
            unlink( $file );
    }

    if ( is_dir( $dir ) ){
                //echo $dir;
               // die;
        rmdir( $dir ); // remove the directory itself (rmdir only removes a directory once it is empty)

            }
      }

// remove all .svn directories in the 
// current directory and sub directories 
// (recursively applied)
removeSVN($path);
