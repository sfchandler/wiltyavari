<?php
/**************************************************************************************************************

    NAME
	PdfToText.phpclass

    DESCRIPTION
    	A class for extracting text from Pdf files.
	Usage is very simple : just instantiate a PdfToText object, specifying an input filename, then use the
	Text property to retrieve PDF textual contents :

		$pdf	=  new PdfToText ( 'sample.pdf' ) ;
		echo $pdf -> Text ;		// or : echo ( string ) $pdf ;

	Or :

		$pdf	=  new PdfToText ( ) ;
		$pdf -> Load ( 'sample.pdf' ) ;
		echo $pdf -> Text ;

    AUTHOR
        Christian Vigh, 04/2016.

    LICENSE
	To use this file, you must conform to the GNU Lesser General Public License.

    HISTORY
    [Version : 1.2.47]	[Date : 2016/09/13]     [Author : CV]
	. Changed the licensing model from GPL TO LGPL.
	. Specialized the PdfImage class into subclasses. The only available subclass for now is PdfJpegImage.
	. Added the $EncryptMetadata property, coming from encyption information present in the PDF file.

    [Version : 1.0]	[Date : 2016/04/16]     [Author : CV]
        Initial version.

 **************************************************************************************************************/


/*==============================================================================================================

    class PdfToTextException -
        Implements an exception thrown when an error is encountered while decoding PDF files.

  ==============================================================================================================*/
class  PdfToTextException	extends  Exception
   { 
	public static	$IsObject		=  false ;

	public function  __construct ( $message, $object_id = false )
	   {
		$text	=  "Pdf decoding error" ;

		if  ( $object_id  !==  false )
			$text	.=  " (object #$object_id)" ;

		$text	.=  " : $message" ;

		parent::__construct ( $text ) ;
	    }
    }


if  ( ! function_exists ( 'warning' ) )
   {
	function  warning ( $message )
	   {
		trigger_error ( $message, E_USER_WARNING ) ;
	    }
    }


if  ( ! function_exists ( 'error' ) )
   {
	function  error ( $message )
	   {
		if  ( is_string ( $message ) )
			trigger_error ( $message, E_USER_ERROR ) ;
		else if (  is_a ( $message, '\Exception' ) )
			throw $message ;
	    }
    }


/*==============================================================================================================

    class PfObjectBase -
        Base class for all PDF objects defined here.

  ==============================================================================================================*/
abstract class  PdfObjectBase		// extends  Object
   {
	// Possible encoding types for streams inside objects ; "unknown" means that the object contains no stream
	const 	PDF_UNKNOWN_ENCODING 		=   0 ;		// No stream decoding type could be identified
	const 	PDF_ASCIIHEX_ENCODING 		=   1 ;		// AsciiHex encoding - not tested
	const 	PDF_ASCII85_ENCODING		=   2 ;		// Ascii85 encoding - not tested
	const 	PDF_FLATE_ENCODING		=   3 ;		// Flate/deflate encoding
	const	PDF_TEXT_ENCODING		=   4 ;		// Stream data appears in clear text - no decoding required
	const	PDF_LZW_ENCODING		=   5 ;		// Not implemented yet
	const	PDF_RLE_ENCODING		=   6 ;		// Runtime length encoding ; not implemented yet
	const	PDF_DCT_ENCODING		=   7 ;		// JPEG images
	const	PDF_CCITT_FAX_ENCODING		=   8 ;		// CCITT Fax encoding - not implemented yet
	const	PDF_JBIG2_ENCODING		=   9 ;		// JBIG2 filter encoding (black/white) - not implemented yet
	const	PDF_JPX_ENCODING		=  10 ;		// JPEG2000 encoding - not implemented yet


	public function  __construct ( )
	   { 
		// parent::__construct ( ) ; 
	    }



 	/*--------------------------------------------------------------------------------------------------------------

	    GetUTCDate -
	        Reformats an Adobe UTC date to a format that can be understood by the strtotime() function.
		Dates are specified in the following format :
			D:20150521154000Z
			D:20160707182114+02
		with are both recognized by strtotime(). However, another format can be specified :
			D:20160707182114+02'00'
		which is not recognized by strtotime() so we have to get rid from the '00' part.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  GetUTCDate ( $date )
	   {
		if  ( ( $date [0]  ==  'D'  ||  $date [0]  ==  'd' )  &&  $date [1]  ==  ':' )
			$date	=  substr ( $date, 2 ) ;

		if  ( ( $index  =  strpos ( $date, "'" ) )  !==  false )
			$date	=  substr ( $date, 0, $index ) ;

		return ( $date ) ;
	    }


 	/*--------------------------------------------------------------------------------------------------------------

	    IsCharacterMap -
	        Checks if the specified text contents represent a character map definition or not.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsCharacterMap  ( $decoded_data )
	   {
		return ( stripos ( $decoded_data, 'begincmap' )  	!==  false  ||
			 stripos ( $decoded_data, 'beginbfchar' )  	!==  false  ||		// "begincmap" does not seem to be mandatory...
			 stripos ( $decoded_data, 'beginbfrange' )  	!==  false  ||	
			 stripos ( $decoded_data, '/Differences' )	!==  false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    IsFont -
		Checks if the current object contents specify a font declaration.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsFont ( $object_data )
	   {
		return ( stripos ( $object_data, '/BaseFont' )  !==  false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    IsFontMap -
		Checks if the code contains things like :
			<</F1 26 0 R/F2 22 0 R/F3 18 0 R>>
		which maps font 1 (when specified with the /Fx instruction) to object 26, 2 to object 22 and 3 to 
		object 18, respectively, in the above example.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsFontMap ( $object_data )
	   {
		if  ( preg_match ( '#<< \s* ( (/F \d+) | (/R \d+) | (/f-\d+-\d+) | (/[CT]\d+_\d+) | (/TT \d+) ) \s+ .* >>#msx', $object_data ) )
			return ( true ) ;
		else
			return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    IsImage -
		Checks if the code contains things like :
			/Subtype/Image

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsImage ( $object_data )
	   {
		if  ( preg_match ( '#/Subtype \s* /Image#msx', $object_data ) )
			return ( true ) ;
		else
			return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    IsObjectStream -
		Checks if the code contains an object stream (/Type/ObjStm)
			/Subtype/Image

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsObjectStream ( $object_data ) 
	   {
		if  ( preg_match ( '#/Type \s* /ObjStm#isx', $object_data ) )
			return ( true ) ;
		else
			return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    NAME
	        IsPageHeaderOrFooter - Check if the specified object contents denote a text stream.

	    PROTOTYPE
	        $status		=  $this -> IsText ( $object_data, $decoded_stream_data ) ;

	    DESCRIPTION
	        Checks if the specified decoded stream contents denotes header or footer data.

	    PARAMETERS
	        $stream_data (string) -
	                Decoded stream contents.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsPageHeaderOrFooter ( $stream_data )
	   {
		if  ( preg_match ( '#/Type \s* /Pagination \s* /Subtype \s*/((Header)|(Footer))#ix', $stream_data ) )
			return ( true ) ;
		else if  ( preg_match ( '#/Attached \s* \[ .*? /((Top)|(Bottom)) [^]]#ix', $stream_data ) )
			return ( true ) ;
		else
			return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    NAME
	        IsText - Check if the specified object contents denote a text stream.

	    PROTOTYPE
	        $status		=  $this -> IsText ( $object_data, $decoded_stream_data ) ;

	    DESCRIPTION
	        Checks if the specified object contents denote a text stream.

	    PARAMETERS
	        $object_data (string) -
	                Object data, ie the contents located between the "obj" and "endobj" keywords.

	        $decoded_stream_data (string) -
	        	The flags specified in the object data are not sufficient to be sure that we have a block of
	        	drawing instructions. We must also check for certain common instructions to be present.

	    RETURN VALUE
	        True if the specified contents MAY be text contents, false otherwise.

	    NOTES
		I do not consider this method as bullet-proof. There may arise some cases where non-text blocks can be
		mistakenly considered as text blocks, so it is subject to evolve in the future.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsText ( $object_data, $decoded_stream_data )
	   {
		if  ( stripos ( $object_data, '/Filter'  )  !==  false  &&
		      stripos ( $object_data, '/Length'  )  !==  false  &&
		      stripos ( $object_data, '/Length1' )  ===  false  &&
		      stripos ( $object_data, '/Type'    )  ===  false  &&
		      stripos ( $object_data, '/Subtype' )  ===  false )
		   {
		   	if  ( preg_match ( '/\\b(BT|Tf|Td|TJ|Tj|Tm)\\b/', $decoded_stream_data ) )
				return ( true ) ;
		    }
		else if  ( preg_match ( '/\\b(BT|Tf|Td|TJ|Tj|Tm)\\b/', $decoded_stream_data ) )
			return ( true ) ;

		return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    NAME
	        GetEncodingType - Gets an object encoding type.

	    PROTOTYPE
	        $type	=  $this -> GetEncodingType ( $object_id, $object_data ) ;

	    DESCRIPTION
	        When an object is a stream, returns its encoding type.

	    PARAMETERS
		$object_id (integer) -
			PDF object number.

	        $object_data (string) -
	                Object contents.

	    RETURN VALUE
	        Returns one of the following values :

		- PdfToText::PDF_ASCIIHEX_ENCODING :
			Hexadecimal encoding of the binary values.
			Decoding algorithm was taken from the unknown contributor and not tested so far, since I
			couldn't find a PDF file with such an encoding type.

		- PdfToText::PDF_ASCII85_ENCODING :
			Obscure encoding format.
			Decoding algorithm was taken from the unknown contributor and not tested so far, since I
			couldn't find a PDF file with such an encoding type.

		- PdfToText::PDF_FLATE_ENCODING :
			gzip/deflate encoding.

		- PdfToText::PDF_TEXT_ENCODING :
			Stream data is unencoded (ie, it is pure ascii).

		- PdfToText::PDF_UNKNOWN_ENCODING :
			The object data does not specify any encoding at all. It can happen on objects that do not have
			a "stream" part.

		- PdfToText::PDF_DCT_ENCODING :
			a lossy filter based on the JPEG standard.

		The following constants are defined but not yet implemented ; an exception will be thrown if they are
		encountered somewhere in the PDF file :

		- PDF_LZW-ENCODING :
			a filter based on LZW Compression; it can use one of two groups of predictor functions for more 
			compact LZW compression : Predictor 2 from the TIFF 6.0 specification and predictors (filters) 
			from the PNG specification

		- PDF_RLE_ENCODING :
			a simple compression method for streams with repetitive data using the run-length encoding 
			algorithm and the image-specific filters.

		PDF_CCITT_FAX_ENCODING :
			a lossless bi-level (black/white) filter based on the Group 3 or Group 4 CCITT (ITU-T) fax 
			compression standard defined in ITU-T T.4 and T.6.

		PDF_JBIG2_ENCODING :
			a lossy or lossless bi-level (black/white) filter based on the JBIG2 standard, introduced in 
			PDF 1.4.

		PDF_JPX_ENCODING :
			a lossy or lossless filter based on the JPEG 2000 standard, introduced in PDF 1.5.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  GetEncodingType ( $object_id, $object_data )
	   {
		$status 	=  preg_match ( '# / (?P<encoding> (ASCIIHexDecode) | (ASCII85Decode) | (FlateDecode) | (DCTDecode) | ' .
						                   '(LZWDecode) | (RunLengthDecode) | (CCITTFaxDecode) | (JBIG2Decode) | (JPXDecode) ) #imsx', $object_data, $match ) ;

		if  ( ! $status )
			return ( self::PDF_TEXT_ENCODING ) ;

		switch ( strtolower ( $match [ 'encoding' ] ) )
		    {
		    	case 	'asciihexdecode' 	:  return ( self::PDF_ASCIIHEX_ENCODING  ) ;
		    	case 	'ascii85decode' 	:  return ( self::PDF_ASCII85_ENCODING   ) ;
		    	case	'flatedecode'		:  return ( self::PDF_FLATE_ENCODING     ) ;
			case    'dctdecode'		:  return ( self::PDF_DCT_ENCODING       ) ;

			case	'ccittfaxdecode'	:  // return ( self::PDF_CCITT_FAX_ENCODING ) ;

			case	'lzwdecode'		:
			case	'runlengthdecode'	: 
			case	'jbig2decode'		: 
			case	'jpxdecode'		:
				if  ( PdfToText::$DEBUG  >  1 )
					error ( "Encoding type \"{$match [ 'encoding' ]}\" not yet implemented for pdf object #$object_id." ) ;

			default				:  return ( self::PDF_UNKNOWN_ENCODING  ) ;
		     }
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        GetObjectReferences - Gets object references from a specified construct.
	
	    PROTOTYPE
	        $status		=  $this -> GetObjectReferences ( $object_id, $object_data, $searched_string, &$object_ids ) ;
	
	    DESCRIPTION
	        Certain parameter specifications are followed by an object reference of the form :
			x 0 R
		but it can also be an array of references :
			[x1 0 R x2 0 R ... xn 0 r]
		Those kind of constructs can occur after parameters such as : /Pages, /Contents, /Kids...
		This method extracts the object references found in such a construct.
	
	    PARAMETERS
	        $object_id (integer) -
	                Id of the object to be analyzed.

		$object_data (string) -
			Object contents.

		$searched_string (string) - 
			String to be searched, that must be followed by an object or an array of object references.
			This parameter can contain constructs used in regular expressions. Note however that the '#'
			character must be escaped, since it is used as a delimiter in the regex that is applied on
			object data.

		$object_ids (array of integers) -
			Returns on output the ids of the pdf object that have been found after the searched string.
	
	    RETURN VALUE
	        True if the searched string has been found and is followed by an object or array of object references,
		false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  GetObjectReferences ( $object_id, $object_data, $searched_string, &$object_ids )
	   {
		$status		=  true ;
		$object_ids	=  [] ;

		if  ( preg_match ( "#$searched_string \s+ (?P<object> \d+) \s+ \d+ \s+ R#ix", $object_data, $match ) )
		   {
			$object_ids []	=  ( integer ) $match [ 'object' ] ;
		    }
		else if  ( preg_match ( "#$searched_string \s* \\[ (?P<objects> [^\]]+ ) \\]#ix", $object_data, $match ) )
		   {
			$object_list	=  $match [ 'objects' ] ;

			if  ( preg_match_all ( '/(?P<object> \d+) \s+ \d+ \s+ R/x', $object_list, $matches ) )
			   {
				foreach  ( $matches [ 'object' ]  as  $id )
					$object_ids []	=  ( integer ) $id ;
			    }
			else
				$status		=  false ;
		    }
		else
			$status		=  false ;

		return ( $status ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        CodePointToUtf8 - Encodes a Unicode codepoint to UTF8.
	
	    PROTOTYPE
	        $char	=  $this -> CodePointToUtf8 ( $code ) ;
	
	    DESCRIPTION
	        Encodes a Unicode codepoint to UTF8, trying to handle all possible cases.
	
	    PARAMETERS
	        $code (integer) -
	                Unicode code point to be translated.
	
	    RETURN VALUE
	        A string that contains the UTF8 bytes representing the Unicode code point.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  CodePointToUtf8 ( $code )
	   {
		if  ( $code )
		   {
			$result		=  '' ;

			while  ( $code )
			   {
				$entity		  =  '&#x' . sprintf ( '%x', ( $code & 0xFFFF ) ) . ';' ;
				$result		  =  mb_convert_encoding ( $entity, 'UTF-8', 'HTML-ENTITIES' ) . $result ;
				$code		>>=  16 ;
			    }

			return ( $result ) ;
		    }
		// No translation is apparently possible : use a placeholder to signal this situation
		else
		   {
			if  ( strpos ( PdfToText::$Utf8Placeholder, '%' )   ===  false )
			   {
				return ( PdfToText::$Utf8Placeholder ) ;
			    }
			else 
				return ( sprintf ( PdfToText::$Utf8Placeholder, $code ) ) ;
		    }
	    }
    }


/*==============================================================================================================

    PdfToText class -
	A class for extracting text from Pdf files.

 ==============================================================================================================*/
class  PdfToText 	extends PdfObjectBase
   {
	// Current version of the class
	const		VERSION					=  "1.2.47" ;

	// Pdf processing options
	const		PDFOPT_NONE				=  0x0000 ;		// No extra option
	const		PDFOPT_REPEAT_SEPARATOR			=  0x0001 ;		// Repeats the Separator property if the offset between two text blocks (in array notation)
											// is greater than $this -> MinSpaceWidth
	const		PDFOPT_GET_IMAGE_DATA			=  0x0002 ;		// Retrieve raw image data in the $ths -> ImageData array
	const		PDFOPT_DECODE_IMAGE_DATA		=  0x0004 ;		// Creates a jpeg resource for each image
	const		PDFOPT_IGNORE_TEXT_LEADING		=  0x0008 ;		// Ignore text leading values
	const		PDFOPT_NO_HYPHENATED_WORDS		=  0x0010 ;		// Join hyphenated words that are split on two lines

	// Encryption standards (to be completed, as more crazy PDF samples arise...)
	const		PDFCRYPT_NONE				=  0 ;			// PDF file is not encrypted 
	const		PDFCRYPT_STANDARD			=  1 ;			// Standard security handler

	// A 32-bytes hardcoded padding used when computing encryption keys
	const		PDF_ENCRYPTION_PADDING			=  "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A" ;

	// Permission bits for encrypted files. Comments come from the PDF specification
	const		PDFPERM_PRINT				=  0x0004 ;		// bit 3 :
											//	(Revision 2) Print the document.
											//	(Revision 3 or greater) Print the document (possibly not at the highest quality level, 
											//	depending on whether bit 12 is also set).
	const		PDFPERM_MODIFY				=  0x0008 ;		// bit 4 :
											//	Modify the contents of the document by operations other than those controlled by bits 6, 9, and 11.
	const		PDFPERM_COPY				=  0x0010 ;		// bit 5 :
											//	(Revision 2) Copy or otherwise extract text and graphics from the document, including extracting text 
											//	and graphics (in support of accessibility to users with disabilities or for other purposes).
											//	(Revision 3 or greater) Copy or otherwise extract text and graphics from the document by operations  
											//	other than that controlled by bit 10.
	const		PDFPERM_MODIFY_EXTRA			=  0x0020 ;		// bit 6 :
											//	Add or modify text annotations, fill in interactive form fields, and, if bit 4 is also set, 
											//	create or modify interactive form fields (including signature fields).
	const		PDFPERM_FILL_FORM			=  0x0100 ;		// bit 9 :
											//	(Revision 3 or greater) Fill in existing interactive form fields (including signature fields), 
											//	even if bit 6 is clear.
	const		PDFPERM_EXTRACT				=  0x0200 ;		// bit 10 :
											//	(Revision 3 or greater) Fill in existing interactive form fields (including signature fields), 
											//	even if bit 6 is clear.
	const		PDFPERM_ASSEMBLE			=  0x0400 ;		// bit 11 :
											//	(Revision 3 or greater) Assemble the document (insert, rotate, or delete pages and create bookmarks 
											//	or thumbnail images), even if bit 4 is clear.
	const		PDFPERM_HIGH_QUALITY_PRINT		=  0x0800 ;		// bit 12 :
											//	(Revision 3 or greater) Print the document to a representation from which a faithful digital copy of 
											//	the PDF content could be generated. When this bit is clear (and bit 3 is set), printing is limited to 
											//	a low-level representation of the appearance, possibly of degraded quality. 

	// When boolean true, outputs debug information about fonts, character maps and drawing contents.
	// When integer > 1, outputs additional information about other objects.
	public static 		$DEBUG 			=  false ;

	// Current filename
	public 		$Filename 			=  false ;
	// Extracted text
	public		$Text				=  '' ;
	// Document pages (array of strings)
	public		$Pages				=  [] ;
	// Document images (array of PdfImage objects)
	public		$Images				=  [] ;
	// Raw data for document images
	public		$ImageData			=  [] ;
	// Text chunk separator (used to separate blocks of text specified as an array notation)
	public		$BlockSeparator			=  '' ;
	// Separator used to separate text groups where the offset value is less than -1000 thousands of character units
	// (eg : [(1)-1822(2)] will add a separator between the characters "1" and "2")
	// Note that such values are expressed in thousands of text units and subtracted from the current position. A 
	// negative value means adding more space between the two text units it separates.
	public		$Separator			=  ' ' ;
	// Separator to be used between pages in the $Text property
	public		$PageSeparator			=  "\n" ;
	// Minimum value (in 1/1000 of text units) that separates two text chunks that can be considered as a real space
	public		$MinSpaceWidth			=  250 ;
	// Pdf options
	public		$Options			=  self::PDFOPT_NONE ;
	// Author information 
	public		$Author				=  '' ;
	public		$CreatorApplication		=  '' ;
	public		$ProducerApplication		=  '' ;
	public		$CreationDate			=  '' ;
	public		$ModificationDate		=  '' ;
	public		$Title				=  '' ;
	private		$GotAuthorInformation		=  false ;
	// Unique and arbitrary file identifier, as specified in the PDF file
	// Well, in fact, there are two IDs, but the PDF specification does not mention the goal of the second one
	public		$ID				=  '' ;
	public		$ID2				=  '' ; 
	//Encryption-related properties 
	public		$UserPassword			=  false ;
	public		$OwnerPassword			=  false ;
	public		$IsPasswordProtected		=  false ;	// Will be set to true is encryption information has been found
	// End of line string
	public		$EOL				=  PHP_EOL ;
	// String to be used when no Unicode translation is possible
	public static	$Utf8Placeholder		=  '' ;

	// Font mappings
	protected 	$FontTable			=  false ;
	// Page map object
	protected	$PageMap ;
	// Page locations (start and end offsets)
	protected	$PageLocations ;

	// Indicates whether global static initializations have been made
	// This is mainly used for variables such as $Utf8PlaceHolder, which is initialized to a different value
	private static	$StaticInitialized		=  false ;

	// Drawing instructions that are to be ignored and removed from a text stream before processing, for performance 
	// reasons (it is faster to call preg_replace() once to remove them than calling the __next_instruction() and 
	// __next_token() methods to process an input stream containing such useless instructions)
	// This is an array of regular expressions where the following constructs are replaced at runtime during static
	// initialization :
	// %n - Will be replaced with a regex matching a decimal number.
	private static  $IgnoredInstructionsTemplates	=
	   [
		'%n{6} ( (c) | (cm) ) \s+',
		'%n{4} ( (re) | (y) | (v) ) \s+',
		'%n{3} ( (scn) | (SCN) | (r) | (rg) | (RG) ) \s+',
		'%n{2} ( (m) | (l) ) \s+',
		'%n ( (w) | (M) | (g) | (G) | (J) ) \s+',
		'\b ( (BDC) | (BT) | (ET) | (EMC) ) \s+',
		'\/( (CS \d+) | (GS \d+) | (Fm \d+) | (Im \d+) | (PlacedGraphic) ) \s+ \w+ \s*',
		'\/Span \s* << .*? >> [ \t\r\n>]*',
		'\/PlacedGraphic \s+',
		'\d+ \s+ ( (scn) | (SCN) )',
		'\/MC \d+ \s+',
		 '^ \s* [QqfhS] \r? \n',
		 '^W \s+ n \r? \n',
		 '^q \s+ [hfS] \r? \n'
	    ] ;
	// Replacement regular expressions for %something constructs specified in the $IgnoredInstructions array
	private static	$ReplacementConstructs		=
	    [
		'%n'	=>  '( [+\-]? ( ( [0-9]+ ( \. [0-9]* )? ) | ( \. [0-9]+ ) ) \s+ )'
	     ] ;
	// The final regexes that are built during static initialization by the __build_ignored_instructions() method
	private static  $IgnoredInstructions		=  [] ;

	// Font information buffer - another cache used by the __next_instruction() method to avoid repeated calls
	// to the IsMapped() and GetMapWidth() methods
	private		$FontInformationBuffer		=  [] ;

	// Map id buffer - for avoiding unneccesar calls to GetFontByMapId
	private		$MapIdBuffer			=  [] ;

	// Regex used for removing hyphens - we have to take care of different line endings : "\n" for Unix, "\r\n"
	// for Windows, and "\r" for pure Mac files.
	// Note that we replace an hyphen followed by an end-of-line then by non-space characters with the non-space
	// characters, so the word gets joined on the same line. Spaces after the end of the word (on the next line)
	// are removed, in order for the next word to appear at the beginning of the second line.
	private static $RemoveHyphensRegex		=  '#
								( 
									  -
									  [ \t]* ( (\r\n) | \n | \r )+ [ \t\r\n]*
								 )
								([^ \t\r\n]+)
								\s*
							    #msx' ;

	// A small list of Unicode character ranges that are related to languages written from right to left
	// For performance reasons, everythings is mapped to a range here, even if it includes codepoints that do not map to anything
	// (this class is not a Unicode codepoint validator, but a Pdf text extractor...)
	// To be completed !
	private static	$RtlCharacters		=
	   [
		// This range represents the following languages :
		// - Hebrew			(0590..05FF)
		// - Arabic			(0600..06FF)
		// - Syriac			(0700..074F)
		// - Supplement for Arabic	(0750..077F)
		// - Thaana			(0780..07BF)
		// - N'ko			(07C0..07FF)
		// - Samaritan			(0800..083F)
		// - Mandaic			(0840..085F)
		[ 0x00590, 0x0085F ],
		// Hebrew supplement (I suppose ?) + other characters
		[ 0x0FB1D, 0x0FEFC ],
		// Mende kikakui
		[ 0x1E800, 0x1E8DF ],
		// Adlam
		[ 0x1E900, 0x1E95F ],
		// Others
		[ 0x10800, 0x10C48 ],
		[ 0x1EE00, 0x1EEBB ]
	    ] ;
	// As usual, caching a little bit the results of the IsRtlCharacter() method is welcome. Each item will have the value true if the
	// character is RTL, or false if LTR.
	private		$RtlCharacterBuffer		=  [] ;

	// Encryption information coming from the PDF file
	public		$EncryptionMode			=  self::PDFCRYPT_NONE ;	// NONE means : file is not encrypted
	public		$EncryptionAlgorithm		=  false ;			// Encryption algorithm version (/V flag)
	public		$EncryptionAlgorithmRevision	=  false ;			// Encryption algorithm revision (/R flag)
	public		$UserEncryptionKey		=  false ;			// Encryption key for the user password (/U flag)
	public		$OwnerEncryptionKey		=  false ;			// Encryption key for the owner password (/O flag)
	public		$EncryptionFlags		=  false ;			// Encryption flags (/P flags, a combination of PDFPERM_* bits)
	public		$EncryptionKeyLength		=  false ;			// Encryption key length (/Length flag)
	public		$EncryptMetadata		=  false ;			// Tells whether metadata has been encrypted or not (/EncryptMetadata flag)

	// Computed encryption key
	protected	$EncryptionKey			=  false ;		

	// A subset of a character classification array that avoids too many calls to the ctype_* functions or too many
	// character comparisons.
	// This array is used only for highly sollicited parts of code
	const	CTYPE_ALPHA		=  0x01 ;		// Letter
	const	CTYPE_DIGIT		=  0x02 ;		// Digit
	const	CTYPE_ALNUM		=  0x03 ;		// Letter or digit
	const	CTYPE_XDIGIT		=  0x04 ;		// Hex digit
	const	CTYPE_XALNUM		=  0x07 ;		// A synonym for CTYPE_ALPHA | CTYPE_DIGIT | CTYPE_XDIGIT
	const	CTYPE_XNUM		=  0x06 ;		// A synonym for CTYPE_DIGIT | CTYPE_XDIGIT

	private static  $CharacterClass		=  
	   [
		'a' => self::CTYPE_XALNUM, 'b' => self::CTYPE_XALNUM, 'c' => self::CTYPE_XALNUM, 'd' => self::CTYPE_XALNUM, 'e' => self::CTYPE_XALNUM, 'f' => self::CTYPE_XALNUM, 
		'g' => self::CTYPE_ALNUM , 'h' => self::CTYPE_ALNUM , 'i' => self::CTYPE_ALNUM , 'j' => self::CTYPE_ALNUM , 'k' => self::CTYPE_ALNUM , 'l' => self::CTYPE_ALNUM , 
		'm' => self::CTYPE_ALNUM , 'n' => self::CTYPE_ALNUM , 'o' => self::CTYPE_ALNUM , 'p' => self::CTYPE_ALNUM , 'q' => self::CTYPE_ALNUM , 'r' => self::CTYPE_ALNUM , 
		's' => self::CTYPE_ALNUM , 't' => self::CTYPE_ALNUM , 'u' => self::CTYPE_ALNUM , 'v' => self::CTYPE_ALNUM , 'w' => self::CTYPE_ALNUM , 'x' => self::CTYPE_ALNUM , 
		'y' => self::CTYPE_ALNUM , 'z' => self::CTYPE_ALNUM , 
		'A' => self::CTYPE_XALNUM, 'B' => self::CTYPE_XALNUM, 'C' => self::CTYPE_XALNUM, 'D' => self::CTYPE_XALNUM, 'E' => self::CTYPE_XALNUM, 'F' => self::CTYPE_XALNUM, 
		'G' => self::CTYPE_ALNUM , 'H' => self::CTYPE_ALNUM , 'I' => self::CTYPE_ALNUM , 'J' => self::CTYPE_ALNUM , 'K' => self::CTYPE_ALNUM , 'L' => self::CTYPE_ALNUM , 
		'M' => self::CTYPE_ALNUM , 'N' => self::CTYPE_ALNUM , 'O' => self::CTYPE_ALNUM , 'P' => self::CTYPE_ALNUM , 'Q' => self::CTYPE_ALNUM , 'R' => self::CTYPE_ALNUM , 
		'S' => self::CTYPE_ALNUM , 'T' => self::CTYPE_ALNUM , 'U' => self::CTYPE_ALNUM , 'V' => self::CTYPE_ALNUM , 'W' => self::CTYPE_ALNUM , 'X' => self::CTYPE_ALNUM , 
		'Y' => self::CTYPE_ALNUM , 'Z' => self::CTYPE_ALNUM , 
		'0' => self::CTYPE_XNUM  , '1' => self::CTYPE_XNUM  , '2' => self::CTYPE_XNUM  , '3' => self::CTYPE_XNUM  , '4' => self::CTYPE_XNUM  , '5' => self::CTYPE_XNUM  , 
		'6' => self::CTYPE_XNUM  , '7' => self::CTYPE_XNUM  , '8' => self::CTYPE_XNUM  , '9' => self::CTYPE_XNUM
	    ] ;


	/*--------------------------------------------------------------------------------------------------------------

	    CONSTRUCTOR
	        $pdf	=  new PdfToText ( $filename = null, $options = PDFOPT_NONE ) ;

	    DESCRIPTION
	        Builds a PdfToText object and optionally loads the specified file's contents.

	    PARAMETERS
	        $filename (string) -
	                Optional PDF filename whose text contents are to be extracted.

		$options (integer) -
			A combination of PDFOPT_* flags. This can be any of the following :

			- PDFOPT_REPEAT_SEPARATOR :
				Text constructs specified as an array are separated by an offset which is expressed as
				thousands of text units ; for example :

					[(1)-2000(2)]

				will be rendered as the text "1  2" ("1" and "2" being separated by two spaces) if the
				"Separator" property is set to a space (the default) and this flag is specified.
				When not specified, the text will be rendered as "1 2".

			- PDFOPT_NONE :
				None of the above options will apply.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function  __construct ( $filename = null, $options = self::PDFOPT_NONE, $user_password = false, $owner_password = false )
	   {
		// Perform static initializations if needed
		if  ( ! self::$StaticInitialized )
		   {
			if  ( self::$DEBUG )
			   {
				// In debug mode, initialize the utf8 placeholder only if it still set to its default value, the empty string
				if  ( self::$Utf8Placeholder  ==  '' )
					self::$Utf8Placeholder	=  '[Unknown character : 0x%08X]' ;
			    }

			// Build the list of regular expressions from the list of ignored instruction templates
			self::__build_ignored_instructions (  ) ;

			self::$StaticInitialized	=  true ;
		    }

		parent::__construct ( ) ;

		$this -> Options	=  $options ;

		if  ( $filename )
			$this -> Load ( $filename, $user_password, $owner_password ) ;
	    }


	public function  __tostring ( )
	   { return ( $this -> Text ) ; }


	/**************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 ******                                          PUBLIC METHODS                                          ******
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 **************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************/

	/*--------------------------------------------------------------------------------------------------------------

	    NAME
	        Load - Loads text contents from a PDF file.

	    PROTOTYPE
	        $pdf -> Load ( $filename ) ;

	    DESCRIPTION
	        Extracts text contents from the specified PDF file. Once processed, text contents will be available
		through the "Text" property.

	    PARAMETERS
	        $filename (string) -
	                Optional PDF filename whose text contents are to be extracted.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Load ( $filename, $user_password = false, $owner_password = false )
	   {
		// Check if the file exists
		if  ( ! file_exists ( $filename ) )
			error ( new  PdfToTextException ( "File \"$filename\" does not exist." ) ) ;

		// Load its contents
		$contents 	=  file_get_contents ( $filename, FILE_BINARY ) ;

		// Check that this is a PDF file
		if  ( ! preg_match ( '/^ %PDF- (?P<version> \d+ (\. \d+)*) /ix', $contents, $match ) )
			error ( new PdfToTextException ( "File \"$filename\" is not a valid PDF file." ) ) ;

		$this -> PdfVersion 		=  $match [ 'version' ] ;

		// Initializations
		$this -> Text 				=  '' ;
		$this -> FontTable 			=  new PdfTexterFontTable ( ) ;
		$this -> Filename 			=  $filename ;
		$this -> Pages				=  [] ;
		$this -> Images				=  [] ;
		$this -> ImageData			=  [] ;
		$this -> PageMap			=  new PdfTexterPageMap ( ) ;
		$this -> PageLocations			=  [] ;
		$this -> Author				=  '' ;
		$this -> CreatorApplication		=  '' ;
		$this -> ProducerApplication		=  '' ;
		$this -> CreationDate			=  '' ;
		$this -> ModificationDate		=  '' ;
		$this -> Title				=  '' ;
		$this -> GotAuthorInformation		=  false ;
		$this -> ID				=  '' ;
		$this -> ID2				=  '' ; 
		$this -> UserPassword			=  $user_password ;
		$this -> OwnerPassword			=  $owner_password ;
		$this -> EncryptedUserPassword		=  false ;
		$this -> EncryptedOwnerPassword		=  false ;
		$this -> IsPasswordProtected		=  false ;
		$this -> EncryptionMode			=  self::PDFCRYPT_NONE ;
		$this -> EncryptionAlgorithm		=  false ;
		$this -> EncryptionAlgorithmRevision	=  false ;
		$this -> UserEncryptionKey		=  false ;
		$this -> OwnerEncryptionKey		=  false ;
		$this -> EncryptionFlags		=  false ;
		$this -> EncryptionKeyLength		=  false ;
		$this -> EncryptMetadata		=  false ;
		$this -> EncryptionKey			=  false ;

		// Also reset cached information that may come from previous runs
		$this -> FontInformationBuffer		=  [] ;
		$this -> MapIdBuffer			=  [] ;
		$this -> RtlCharacterBuffer		=  [] ;

		// Systematically set the GET_IMAGE_DATA flag if DECODE_IMAGE_DATA is specified
		if  ( $this -> Options  &  self::PDFOPT_DECODE_IMAGE_DATA )
			$this -> Options	|=  self::PDFOPT_GET_IMAGE_DATA ;

		// Extract pdf objects that are enclosed by the "obj" and "endobj" keywords
		if  ( ! preg_match_all ( '/(?P<object_id> \d+) \s+ \d+ \s+ obj (?P<object> .*?) endobj/imsx', $contents, $matches ) )
			return ( false ) ;

		// Extract trailer information, which may contain the ID of an object specifying encryption flags
		$this -> GetTrailerInformation ( $contents ) ;

		// Character maps encountered so far
		$cmaps			=  [] ;

		// Objects can be object streams which in turn contains objects (don't know if more than one nesting level could be encountered, however)
		// It could seem elegant to use recursion to handle that ; however, this method was not designed with recursion in mind, and it would
		// maybe require additional parameters to be passed to the recursive function, as well as putting some local variables here as private
		// properties.
		// The $object_stack array allows us to "flatten" the recursivity and keep track of the series of objects we are currently processing
		// (objects coming pfrom the pdf file and object streams)
		$object_stack		=  [] ;
		array_push ( $object_stack,  [ 'index' => 0, 'matches' => $matches ] ) ;

		// An array that will store object ids as keys and text contents as values
		$text			=  [] ;

		while  ( ( $object_stack_count = count ( $object_stack ) )  >  0 )
		   {
			// Get the last object on the stack, which represents the objects to proess in the most inner level of recursion
			$current_stacked_object		=  $object_stack [ $object_stack_count - 1 ] ;
			$object_matches			=  $current_stacked_object [ 'matches' ] ;
			$start_index			=  $current_stacked_object [ 'index' ] ; 

			// Loop through the objects
			for ( $i = $start_index, $object_match_count = count ( $object_matches [ 'object' ] ) ; $i  <  $object_match_count ; $i ++ )
			   {
		   		$object_data 	=  $object_matches [ 'object' ] [$i] ;
		   		$object_number 	=  ( integer ) $object_matches [ 'object_id' ] [$i] ;

				// Handle the special case of object streams
				if  ( $this -> IsObjectStream ( $object_data ) )
				   {
					// Ignore ill-formed object streams
					if  ( ( $object_stream_matches = $this -> DecodeObjectStream ( $object_number, $object_data ) )  ===  false )
						continue ;

					// First, update the current context of the current object in the object stack.
					// "Updating the context" simply means updating the starting index of the inner for() loop, which will be used the next time
					// this for() loop will be executed for this stacked object list
					$object_stack [ $object_stack_count - 1 ] [ 'index' ]	=  $i + 1 ;

					// Now push the objects coming from the object stream onto the stack
					array_push ( $object_stack, [ 'index' => 0, 'matches' => $object_stream_matches ] ) ;

					// Of course, we need now to process them, before going back to the object list we were currently processing
					// This is done by executing the next iteration of the outer while() loop
					continue 2 ;
				    }

				// Try to catch information related to page mapping - but don't discard the object since it can contain additional information
				$this -> PageMap -> Peek ( $object_number, $object_data ) ;

				// Check if the object contais authoring information - it can appear encoded or unencoded
				if  ( ! $this -> GotAuthorInformation ) 
					$this -> PeekAuthorInformation ( $object_number, $object_data ) ;

				// Also catch the object encoding type
				$type 		=  $this -> GetEncodingType ( $object_number, $object_data ) ;

				if  ( strpos ( $object_data, 'stream' )  ===  false  ||  
						! preg_match ( '#[^/] stream ( (\r? \n) | \r ) (?P<stream> .*?) endstream#imsx', $object_data, $stream_match ) )
				   {
					// Some font definitions are in clear text in an object, some are encoded in a stream within the object
					// We process here the unencoded ones
					if  ( $this -> IsFont ( $object_data ) )
					   {
						$this -> FontTable -> Add ( $object_number, $object_data ) ;
						continue ;
					    }
					// Some character maps may also be in clear text
					else if  ( $this -> IsCharacterMap ( $object_data ) )
					    {
						$cmap	=  PdfTexterCharacterMap::CreateInstance ( $object_number, $object_data ) ;

						if  ( $cmap )
							$cmaps [] 	=  $cmap ;

						continue ;
					    }
					// Check if there is an association between font number and object number
					else if  ( $this -> IsFontMap ( $object_data ) )
		   			   {
						$this -> FontTable -> AddFontMap ( $object_number, $object_data ) ;
						continue ;
					    }
					// Ignore other objects that do not contain an encoded stream
		   			else 
					   {
						if  ( self::$DEBUG  >  1 )
							echo "\n----------------------------------- UNSTREAMED #$object_number\n$object_data" ;

		   				continue ;
					    }
				    }
				// Extract image data, if any
				else if  ( $this -> IsImage ( $object_data ) )
				   {
					$this -> AddImage ( $object_number, $stream_match [ 'stream' ], $type ) ;
					continue ;
				    }

				// Check if the stream contains data (yes, I have found a sample that had streams of length 0...)
				// In other words : ignore empty streams
				if  ( stripos ( $object_data, '/Length 0' )  !==  false )
					continue ;

				// Isolate stream data and try to find its encoding type
				$stream_data 		=  $stream_match [ 'stream' ] ;

				// Ignore this stream if the object does not contain an encoding type (/FLATEDECODE, /ASCIIHEX or /ASCII85)
				if  ( $type  ==  self::PDF_UNKNOWN_ENCODING )
				   {
					if  ( self::$DEBUG  >  1 )
						echo "\n----------------------------------- UNENCODED #$object_number :\n$object_data" ;

					continue ;
				    }

				// Decode the encoded stream
				$decoded_stream_data 	=  $this -> DecodeData ( $object_number, $stream_data, $type ) ;

				// Second chance to peek author information, this time on a decoded stream data
				if  ( ! $this -> GotAuthorInformation )
					$this -> PeekAuthorInformation ( $object_number, $decoded_stream_data ) ;

				// Check for character maps
				if  ( $this -> IsCharacterMap ( $decoded_stream_data ) )
				   {
					$cmap	=  PdfTexterCharacterMap::CreateInstance ( $object_number, $decoded_stream_data ) ;

					if  ( $cmap )
						$cmaps [] 	=  $cmap ;
				   }
				// Font definitions
				else if  ( $this -> IsFont ( $decoded_stream_data ) )
				   {
					$this -> FontTable -> Add ( $object_number, $decoded_stream_data ) ;
				    }
				// Plain text (well, in fact PDF drawing instructions)
				else if  ( $this -> IsText ( $object_data, $decoded_stream_data ) )
				   {
					// We currently ignore page headers and footers
					if  ( ! $this -> IsPageHeaderOrFooter ( $decoded_stream_data ) )
						$text [ $object_number ]	=  $decoded_stream_data ;
					// However, they may be mixed with actual text contents so we need to separate them...
					else
					   {
						$this -> ExtractTextData ( $object_number, $decoded_stream_data, $remainder, $header, $footer ) ;

						// We still need to check again that the extracted text portion contains something useful
						if  ( $this -> IsText ( $object_data, $remainder ) )
							$text [ $object_number ]	=  $remainder ;
					    }
				    }
				else if  ( self::$DEBUG  >  1 )
					echo "\n----------------------------------- UNRECOGNIZED #$object_number :\n$decoded_stream_data\n" ;
			    }

			// All objects at this level has been process, so we can safely remove them from the object stack
			array_pop ( $object_stack ) ;
		    }

		// Associate character maps with declared fonts
		foreach  ( $cmaps  as  $cmap )
			$this -> FontTable -> AddCharacterMap ( $cmap ) ;

		// Current font defaults to -1, which means : take the first available font as the current one.
		// Sometimes it may happen that text drawing instructions do not set a font at all (PdfPro for example)
		$current_font	=  -1 ;

		// Build the page catalog
		$this -> Pages	=  [] ;
		$this -> PageMap -> MapObjects ( $text ) ;

		// Extract text from the collected text elements
		foreach ( $this -> PageMap -> Pages as  $page_number => $page_objects )
		   {
			$this -> Pages [ $page_number ]		=  '' ;

			foreach  ( $page_objects  as  $page_object ) 
			   {
				if  ( isset ( $text [ $page_object ] ) )
				   {
					$object_text				 =  $this -> ExtractText ( $page_object, $text [ $page_object ], $current_font ) ;
					$this -> Pages [ $page_number ]		.=  $object_text ;
				    }
				else if  ( self::$DEBUG  >  1 )
					echo "\n----------------------------------- MISSING OBJECT #$page_object for page #$page_number\n" ;
			    }
		    }

		// Build the page locations (ie, starting and ending offsets)
		$offset		=  0 ;

		foreach  ( $this -> Pages  as  &$page )
		   {
			// If hyphenated words are unwanted, then remove them
			if  ( $this -> Options &  self::PDFOPT_NO_HYPHENATED_WORDS )
				$page	=  preg_replace ( self::$RemoveHyphensRegex, '$4$2', $page ) ;

			$length				 =  strlen ( $page ) ;
			$this -> PageLocations []	 =  [ 'start' => $offset, 'end' => $offset + $length - 1 ] ;
			$offset				+=  $length ;
		    }

		// And finally, the Text property
		$this -> Text	=  implode ( utf8_encode ( $this -> PageSeparator ), $this -> Pages ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        GetPageFromOffset - Returns a page number from a text offset.
	
	    PROTOTYPE
	        $offset		=  $pdf -> GetPageFromOffset ( $offset ) ;
	
	    DESCRIPTION
	        Given a byte offset in the Text property, returns its page number in the pdf document.
	
	    PARAMETERS
	        $offset (integer) -
	                Offset, in the Text property, whose page number is to be retrieved.
	
	    RETURN VALUE
	        Returns a page number in the pdf document, or false if the specified offset does not exist.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  GetPageFromOffset ( $offset )
	   {
		if  ( $offset  ===  false ) 
			return ( false ) ;

		foreach  ( $this -> PageLocations  as  $page => $location )
		   {
			if  ( $offset  >=  $location [ 'start' ]  &&  $offset  <=  $location [ 'end' ] )
				return ( $page ) ;
		    }

		return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        text_strpos, text_stripos - Search for an occurrence of a string.
	
	    PROTOTYPE
	        $result		=  $pdf -> text_strpos  ( $search, $start = 0 ) ;
	        $result		=  $pdf -> text_stripos ( $search, $start = 0 ) ;
	
	    DESCRIPTION
	        These methods behave as the strpos/stripos PHP functions, except that :
		- They operate on the text contents of the pdf file (Text property)
		- They return an array containing the page number and text offset. $result [0] will be set to the page
		  number of the searched text, and $result [1] to its offset in the Text property
	
	    PARAMETERS
	        $search (string) -
	                String to be searched.

		$start (integer) -
			Start offset in the pdf text contents.
	
	    RETURN VALUE
	        Returns an array of two values containing the page number and text offset if the searched string has
		been found, or false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  text_strpos ( $search, $start = 0 )
	   {
		$offset		=  strpos ( $this -> Text, $search, $start ) ;

		if  ( $offset  !==  false )
			return ( [ $this -> GetPageFromOffset ( $offset ), $offset ] ) ;

		return ( false ) ;
	    }


	public function  text_stripos ( $search, $start = 0 )
	   {
		$offset		=  stripos ( $this -> Text, $search, $start ) ;

		if  ( $offset  !==  false )
			return ( [ $this -> GetPageFromOffset ( $offset ), $offset ] ) ;

		return ( false ) ;
	    }




	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        document_strpos, document_stripos - Search for all occurrences of a string.
	
	    PROTOTYPE
	        $result		=  $pdf -> document_strpos  ( $search, $group_by_page = false ) ;
	        $result		=  $pdf -> document_stripos ( $search, $group_by_page = false ) ;
	
	    DESCRIPTION
		Searches for ALL occurrences of a given string in the pdf document. The value of the $group_by_page
		parameter determines how the results are returned :
		- When true, the returned value will be an associative array whose keys will be page numbers and values
		  arrays of offset of the found string within the page
		- When false, the returned value will be an array of arrays containing two entries : the page number
		  and the text offset.

		For example, if a pdf document contains the string "here" at character offset 100 and 200 in page 1, and
		position 157 in page 3, the returned value will be :
		- When $group_by_page is false :
			[ [ 1, 100 ], [ 1, 200 ], [ 3, 157 ] ]
		- When $group_by_page is true :
			[ 1 => [ 100, 200 ], 3 => [ 157 ]
	
	    PARAMETERS
	        $search (string) -
	                String to be searched.

		$group_by_page (boolean) -
			Indicates whether the found offsets should be grouped by page number or not.
	
	    RETURN VALUE
	        Returns an array of page numbers/character offsets (see Description above) or false if the specified
		string does not appear in the document.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  document_strpos ( $text, $group_by_page = false )
	   {
		$length		=  strlen ( $text ) ;

		if  ( ! $length )
			return ( false ) ;

		$result		=  [] ;
		$index		=  0 ;

		while ( ( $index =  strpos ( $this -> Text, $text, $index ) )  !==  false )
		   {
			$page	=  $this -> GetPageFromOffset ( $index ) ;

			if  ( $group_by_page )
				$result [ $page ] []	=  $index ;
			else
				$result []		=  [ $page, $index ] ;

			$index	+=  $length ;
		    }

		return ( $result ) ;
	    }


	public function  document_stripos ( $text, $group_by_page = false )
	   {
		$length		=  strlen ( $text ) ;

		if  ( ! $length )
			return ( false ) ;

		$result		=  [] ;
		$index		=  0 ;

		while ( ( $index =  stripos ( $this -> Text, $text, $index ) )  !==  false )
		   {
			$page	=  $this -> GetPageFromOffset ( $index ) ;

			if  ( $group_by_page )
				$result [ $page ] []	=  $index ;
			else
				$result []		=  [ $page, $index ] ;

			$index	+=  $length ;
		    }

		return ( $result ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        text_match, document_match - Search string using regular expressions.
	
	    PROTOTYPE
	        $status		=  $pdf -> text_match ( $pattern, &$match = null, $flags = 0, $offset = 0 ) ;
	        $status		=  $pdf -> document_match ( $pattern, &$match = null, $flags = 0, $offset = 0 ) ;
	
	    DESCRIPTION
	        text_match() calls the preg_match() PHP function on the pdf text contents, to locate the first occurrence
		of text that matches the specified regular expression.
		document_match() calls the preg_match_all() function to locate all occurrences that match the specified
		regular expression.
		Note that both methods add the PREG_OFFSET_CAPTURE flag when calling preg_match/preg_match_all so you 
		should be aware that all captured results are an array containing the following entries :
		- Item [0] is the captured string
		- Item [1] is its text offset
		- The text_match() and document_match() methods add an extra array item (index 2), which contains the
		  page number where the matched text resides
	
	    PARAMETERS
	        $pattern (string) -
	                Regular expression to be searched.

		$match (any) -
			Output captures. See preg_match/preg_match_all.

		$flags (integer) -
			PCRE flags. See preg_match/preg_match_all.

		$offset (integer) -
			Start offset. See preg_match/preg_match_all.
	
	    RETURN VALUE
	        Returns the number of matched occurrences, or false if the specified regular expression is invalid.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  text_match ( $pattern, &$match = null, $flags = 0, $offset = 0 )
	   {
		$local_match	=  null ;
		$status		=  preg_match ( $pattern, $this -> Text, $local_match, $flags | PREG_OFFSET_CAPTURE, $offset ) ;

		if  ( $status ) 
		   {
			foreach  ( $local_match  as  &$entry )
				$entry [2]	=  $this -> GetPageFromOffset ( $entry [1] ) ;

			$match	=  $local_match ;
		    }

		return ( $status ) ;
	    }


	public function  document_match ( $pattern, &$matches = null, $flags = 0, $offset = 0 )
	   {
		$local_matches	=  null ;
		$status		=  preg_match_all ( $pattern, $this -> Text, $local_matches, $flags | PREG_OFFSET_CAPTURE, $offset ) ;

		if  ( $status ) 
		   {
			foreach  ( $local_matches  as  &$entry )
			   {
				foreach  ( $entry  as  &$subentry )
				$subentry [2]	=  $this -> GetPageFromOffset ( $subentry [1] ) ;
			    }

			$matches	=  $local_matches ;
		    }

		return ( $status ) ;
	    }


	/**************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 ******                                         INTERNAL METHODS                                         ******
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 **************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************/

	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        AddImage - Adds an image from the PDF stream to the current object.
	
	    PROTOTYPE
	        $this -> AddImage ( $object_id, $stream_data, $type ) ;
	
	    DESCRIPTION
	        Adds an image from the PDF stream to the current object.
		If the PDFOPT_GET_IMAGE_DATA flag is enabled, image data will be added to the ImageData property.
		If the PDFOPT_DECODE_IMAGE_DATA flag is enabled, a jpeg resource will be created and added into the
		Images array property.
	
	    PARAMETERS
	        $object_id (integer) -
	                Pdf object id.

		$stream_data (string) -
			Contents of the unprocessed stream data containing the image.

		$type (integer) -
			One of the PdfToText::PDF_*_ENCODING constants.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  AddImage ( $object_id, $stream_data, $type )
	   {
		if  ( $this -> Options  &  self::PDFOPT_GET_IMAGE_DATA )
		    {
			switch  ( $type )  
			   {
				case	self::PDF_DCT_ENCODING :
					$this -> ImageData	=  [ 'type' => 'jpeg', 'data' => $stream_data ] ;
					break ;
			    }

		     }

		if  ( $this -> Options  &  self::PDFOPT_DECODE_IMAGE_DATA )
		   {
			$image	=  $this -> DecodeImage ( $object_id, $stream_data, $type ) ;

			if  ( $image  !==  false )
				$this -> Images []	=  $image ;
		    }
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    NAME
	        DecodeData - Decodes stream data.

	    PROTOTYPE
	        $data	=  $this -> DecodeData ( $object_id, $stream_data, $type ) ;

	    DESCRIPTION
	        Decodes stream data (binary data located between the "stream" and "enstream" directives) according to the
		specified encoding type, given in the surrounding object parameters.

	    PARAMETERS
		$object_id (integer) -
			Id of the object containing the data.

	        $stream_data (string) -
	                Contents of the binary stream.

		$type (integer) -
			One of the PDF_*_ENCODING constants, as returned by the GetEncodingType() method.

	    RETURN VALUE
	        Returns the decoded stream data.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  DecodeData ( $object_id, $stream_data, $type )
	   {
		$decoded_stream_data 	=  '' ;

		switch  ( $type )
		   {
		   	case 	self::PDF_FLATE_ENCODING :
				// Objects in password-protected Pdf files SHOULD be encrypted ; however, it happens that we may encounter normal,
				// unencrypted ones. This is why we always try to gzuncompress them first then, if failed, try to decrypt them
		   		$decoded_stream_data 	=  @gzuncompress ( $stream_data ) ;

				if  ( $decoded_stream_data  ===  false )
				   {
					if  ( $this -> IsPasswordProtected )
					   {
						$decoded_stream_data	=  $this -> Decrypt ( $object_id, $stream_data ) ;

						if  ( $decoded_stream_data  ===  false )
						   {
							if  ( self::$DEBUG  >  1 )
								warning ( new PdfToTextException ( "Unable to decrypt object contents.", $object_id ) ) ;
						    }
					    }
					else if  ( self::$DEBUG  >  1 )
						error ( new PdfToTextException ( "Invalid gzip data.", $object_id ) ) ;
				    }

		   		break ;

		   	case 	self::PDF_ASCIIHEX_ENCODING :
		   		$decoded_stream_data 	=  $this -> __decode_ascii_hex ( $stream_data ) ;
		   		break ;

			case 	self::PDF_ASCII85_ENCODING :
				$decoded_stream_data 	=  $this -> __decode_ascii_85 ( $stream_data ) ;
				break ;

			case	self::PDF_TEXT_ENCODING :
				$decoded_stream_data	=  $stream_data ;
				break ;
		    }

		return ( $decoded_stream_data ) ;
	    }


	// __decode_ascii_hex -
	//	Decoder for /AsciiHexDecode streams.
	private function __decode_ascii_hex ( $input )
	    {
	    	$output 	=  "" ;
	    	$is_odd 		=  true ;
	    	$is_comment 	=  false ;

	    	for  ( $i = 0, $codeHigh =  -1 ; $i  <  strlen ( $input )  &&  $input [ $i ]  !=  '>' ; $i++ )
	    	   {
	    		$c 	=  $input [ $i ] ;

	    		if  ( $is_comment )
	    		   {
	    			if   ( $c  ==  '\r'  ||  $c  ==  '\n' )
	    				$is_comment 	=  false ;

	    			continue;
	    		    }

	    		switch  ( $c )
	    		   {
	    			case  '\0' :
	    			case  '\t' :
	    			case  '\r' :
	    			case  '\f' :
	    			case  '\n' :
	    			case  ' '  :
	    				break ;

	    			case '%' :
	    				$is_comment 	=  true ;
	    				break ;

	    			default :
	    				$code 	=  hexdec ( $c ) ;

	    				if  ( $code  ===  0  &&  $c  !=  '0' )
	    					return ( '' ) ;

	    				if  ( $is_odd )
	    					$codeHigh 	 =  $code ;
					else
	    					$output 	.=  chr ( ( $codeHigh << 4 ) | $code ) ;

	    				$is_odd 	=  ! $is_odd ;
	    				break ;
	    		    }
	    	    }

	    	if  ( $input [ $i ]  !=  '>' )
	    		return ( '' ) ;

	    	if  ( $is_odd )
	    		$output 	.=  chr ( $codeHigh << 4 ) ;

	    	return ( $output ) ;
	    }


	// __decode_ascii_85 -
	//	Decoder for /Ascii85Decode streams.
	private function  __decode_ascii_85 ( $input )
	    {
	    	$output 	=  "" ;
	    	$is_comment 	=  false ;
	    	$ords 		=  [] ;

	    	for  ( $i = 0, $state = 0  ; $i  <  strlen ( $input )  &&  $input [ $i ]  !=  '~'  ; $i ++ )
	    	   {
	    		$c 	=  $input [ $i ] ;

	    		if ( $is_comment )
	    		   {
	    			if  ( $c  ==  '\r'  ||  $c  ==  '\n' )
	    				$is_comment 	=  false ;

	    			continue ;
	    		    }

	    		if  ( $c  ==  '\0'  ||  $c  ==  '\t'  ||  $c  ==  '\r'  ||  $c  ==  '\f'  ||  $c  ==  '\n'  ||  $c  ==  ' ' )
	    			continue ;

    			if  ( $c  ==  '%' )
    			   {
    				$is_comment 	=  true ;
    				continue ;
    			    }

    			if  ( $c  ==  'z'  &&  $state  ===  0 )
    			   {
    				$output 	.= str_repeat ( chr ( 0 ), 4 ) ;
    				continue ;
    			    }

    			if  ( $c  <  '!'  ||  $c  >  'u' )
    				return ( '' ) ;

    			$code = ord ( $input [ $i ] ) & 0xff ;
    			$ords [ $state++  ] = $code - ord ( '!' ) ;

    			if  ( $state  ==  5 )
    			   {
    				$state 	=  0 ;

    				for  ( $sum = 0, $j = 0  ; $j < 5  ; $j++ )
    					$sum 	=  $sum * 85 + $ords [ $j ] ;

    				for ( $j = 3  ; $j >= 0  ; $j-- )
    					$output 	.=  chr ( $sum >> ( $j * 8 ) ) ;
    			    }
	    	    }

	    	if  ( $state === 1 )
	    		return ( '' ) ;
    		elseif  ( $state > 1 )
    		   {
    			for  ( $i = 0, $sum = 0  ; $i  <  $state  ; $i++ )
    				$sum 	+= ( $ords [ $i ] + ( $i == $state - 1 ) ) * pow ( 85, 4 - $i ) ;

    			for  ( $i = 0  ; $i  <  $state - 1  ; $i++ )
    				$output 	.=  chr ( $sum >> ( ( 3 - $i ) * 8 ) ) ;
    		    }

    		return ( $output ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        DecodeImage - Returns decoded image contents.
	
	    PROTOTYPE
	        TBC
	
	    DESCRIPTION
	        description
	
	    PARAMETERS
	        $object_id (integer) -
	                Pdf object number.

		$object_data (string) -
			Object data.

		$type (integer) -
			One of the PdfToText::PDF_*_ENCODING constants.
	
	    RETURN VALUE
	        Returns an object of type PdfIMage, or false if the image encoding type is not currently supported.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  DecodeImage ( $object_id, $object_data, $type )
	   {
		switch  ( $type )  
		   {
			// Normal JPEG image
			case	self::PDF_DCT_ENCODING :
				return ( new PdfJpegImage ( $object_data ) ) ;

			// CCITT fax image
			case	self::PDF_CCITT_FAX_ENCODING :
				return ( new PdfFaxImage ( $object_data ) ) ;

			// For now, I have not found enough information to be able to decode image data in an inflated stream...
			case	self::PDF_FLATE_ENCODING :
				return ( false ) ;

			default :
				error ( new PdfToTextException ( "Image format type #$type not yet implemented for object #$object_id." ) ) ;
		    }
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        DecodeObjectStream - Decodes an object stream.
	
	    PROTOTYPE
	        $array	=  $this -> DecodeObjectStream ( $object_id, $object_data ) ;
	
	    DESCRIPTION
	        Decodes an object stream. An object stream is yet another PDF object type that contains itself several
		objects not defined using the "x y obj ... endobj" syntax.
		As far as I understood, object streams data is contained within stream/endstream delimiters, and is 
		gzipped.
		Object streams start with a set of object id/offset pairs separated by a space ; catenated object data 
		immediately follows the last space ; for example :

			1167 0 1168 114 <</DA(/Helv 0 Tf 0 g )/DR<</Encoding<</PDFDocEncoding 1096 0 R>>/Font<</Helv 1094 0 R/ZaDb 1095 0 R>>>>/Fields[]>>[/ICCBased 1156 0 R]

		The above example specifies two objects :
			. Object #1167, which starts at offset 0 and ends before the second object, at offset #113 in
			  the data. The contents are :
				<</DA(/Helv 0 Tf 0 g )/DR<</Encoding<</PDFDocEncoding 1096 0 R>>/Font<</Helv 1094 0 R/ZaDb 1095 0 R>>>>/Fields[]>>
			. Object #1168, which starts at offset #114 and continues until the end of the object stream.
			  It contains the following data :
				[/ICCBased 1156 0 R]
	
	    PARAMETERS
	        $object_id (integer) -
	                Pdf object number.

		$object_data (string) -
			Object data.
	
	    RETURN VALUE
	        Returns false if any error occurred (mainly for syntax reasons).
		Otherwise, returns an associative array containing the following elements :
		- object_id :
			Array of all the object ids contained in the object stream.
		- object :
			Array of corresponding object data.

		The reason for this format is that it is identical to the array returned by the preg_match() function
		used in the Load() method for finding objects in a PDF file (ie, a regex that matches "x y oj/endobj"
		constructs).
		
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  DecodeObjectStream ( $object_id, $object_data )
	   {
		// Extract gzipped data for this object
		if  ( preg_match ( '#[^/] stream ( (\r? \n) | \r ) (?P<stream> .*?) endstream#imsx', $object_data, $stream_match ) )
		    {
			$stream_data	=  $stream_match [ 'stream' ] ;
			$type 		=  $this -> GetEncodingType ( $object_id, $object_data ) ;
			$decoded_data	=  $this -> DecodeData ( $object_id, $stream_data, $type ) ;
		      }
		// Stay prepared to find one day a sample declared as an object stream but not having gzipped data delimited by stream/endstream tags
		else
		   {
			if  ( self::$DEBUG  >  1 )
				error ( new PdfToTextException ( "Found object stream without gzipped data", $object_id ) ) ;

			return ( false ) ;
		    }

		// Object streams data start with a series of object id/offset pairs. The offset is absolute to the first character
		// after the last space of these series.
		// Note : on Windows platforms, the default stack size is 1Mb. The following regular expression will make Apache crash in most cases,
		// so you have to enable the following lines in your http.ini file to set a stack size of 8Mb, as for Unix systems :
		//	Include conf/extra/httpd-mpm.conf
		//	ThreadStackSize 8388608
		if  ( ! preg_match ( '/^ \s* (?P<series> (\d+ \s+)+ )/x', $decoded_data, $series_match ) )
		   {
			if  ( self::$DEBUG  >  1 )
				error ( new PdfToTextException ( "Object stream does not start with integer object id/offset pairs.", $object_id ) ) ;

			return ( false ) ;
		    }

		// Extract the series of object id/offset pairs and the stream object data
		$series		=  explode ( ' ', rtrim ( str_replace ( '/\s+/', ' ', $series_match [ 'series' ] ) ) ) ;
		$data		=  substr ( $decoded_data, strlen ( $series_match [ 'series' ] ) ) ;

		// $series should contain an even number of values
		if  ( count ( $series ) % 2 )
		   {
			if  ( self::$DEBUG  >  1 )
				error ( new PdfToTextException ( "Object stream should start with an even number of integer values.", $object_id ) ) ;

			return ( false ) ;
		    }

		// Extract every individual object
		$objects	=  [ 'object_id' => [], 'object' => [] ] ;

		for  ( $i = 0, $count = count ( $series ) ; $i  <  $count ; $i += 2 )
		   {
			$object_id	=  ( integer ) $series [$i] ;
			$offset		=  ( integer ) $series [$i+1] ;

			// If there is a "next" object, extract only a substring within the object stream contents
			if  ( isset ( $series [ $i + 3 ] ) ) 
				$object_contents	=  substr ( $data, $offset, $series [ $i + 3 ] - $offset ) ;
			// Otherwise, extract everything until the end
			else
				$object_contents	=  substr ( $data, $offset ) ;

			$objects [ 'object_id'] []	=  $object_id ;
			$objects [ 'object'   ] []	=  $object_contents ;
		    }

		return ( $objects ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Decrypt - Decrypts object data.
	
	    PROTOTYPE
	        $data		=  $this -> Decrypt ( $object_id, $object_data ) ;
	
	    DESCRIPTION
	        Decrypts object data, when the PDF file is password-protected.
	
	    PARAMETERS
	        $object_id (integer) -
	                Pdf object number.

		$object_data (string) -
			Object data.
		
	    RETURN VALUE
	        Returns the decrypted object data, or false if the encrypted object could not be decrypted.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  Decrypt ( $object_id, $object_data ) 
	   {
		return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        ExtractTextData - Extracts text, header & footer information from a text object.
	
	    PROTOTYPE
	        $this -> ExtractTextData ( $object_id, $stream_contents, &$text, &$header, &$footer ) ;
	
	    DESCRIPTION
	        Extracts text, header & footer information from a text object. The extracted text contents will be
		stripped from any header/footer information.
	
	    PARAMETERS
	        $text (string) -
	                Variable that will receive text contents.

		$header, $footer (string) -
			Variables that will receive header and footer information.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  ExtractTextData ( $object_id, $stream_contents, &$text, &$header, &$footer )
	   {
		static		$header_or_footer_re	=  '#
								(?P<contents> 
									<< .*? \[ \s* / (?P<location> (Bottom) | (Top) ) \s* \] .*? >> \s*
									BDC .*? EMC
								 )
							    #imsx' ;

		$header		=
		$footer		=  
		$text		=  '' ;

		if  ( preg_match_all ( $header_or_footer_re, $stream_contents, $matches ) )
		   {
			for  ( $i = 0, $count = count ( $matches [ 'contents' ] ) ; $i  <  $count ; $i ++ )
			   {
				if  ( ! strcasecmp ( $matches [ 'location' ] [$i], 'Bottom' ) )
					$footer		=  $matches [ 'contents' ] [$i] ;
				else
					$header		=  $matches [ 'contents' ] [$i] ;
			    }

			$text	=  preg_replace ( $header_or_footer_re, '', $stream_contents ) ;
		    }
		else
			$text	=  $stream_contents ;
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    NAME
		ExtractText - extracts text from a pdf stream.

	    PROTOTYPE
		$text 	=  $this -> __extract_text ( $object_id, $data, &$current_font ) ;

	    DESCRIPTION
	        Extracts text from decoded stream contents.

	    PARAMETERS
	    	$object_id (integer) -
	    		Object id of this text block.

	    	$data (string) -
	    		Stream contents.

		$current_font (integer) -
			Id of the current font, which should be found in the $this->FontTable property, if anything
			went ok.
			This parameter is required, since text blocks may not specify a new font resource id and reuse
			the one that waas set before.

	    RETURN VALUE
		Returns the decoded text.

	    NOTES
		The PDF language can be seen as a stack-driven language  ; for example, the instruction defining a text
		matrix ( "Tm" ) expects 6 floating-point values from the stack :

			0 0 0 0 x y Tm

		It can also specify specific operators, such as /Rx, which sets font number "x" to be the current font,
		or even "<< >>" constructs that we can ignore during our process of extracting textual data.
		Actually, we only want to handle a very small subset of the Adobe drawing language ; These are :
		- "Tm" instructions, that specify, among others, the x and y coordinates of the next text to be output
		- "/R" instructions, that specify which font is to be used for the next text output. This is useful
		  only if the font has an associated character map.
		- "/F", same as "/R", but use a font map id instead of a direct object id.
		- Text, specified either using a single notation ( "(sometext)" ) or the array notation
		  ( "[(...)d1(...)d2...(...)]" ), which allows for specifying inter-character spacing.
		 - "Tf" instructions, that specifies the font size. This is to be able to compute approximately the
		   number of empty lines between two successive Y coordinates in "Tm" instructions
		 - "TL" instructions, that define the text leading to be used by "T*"

		This is why I choosed to decompose the process of text extraction into three steps :
		- The first one, the lowest-level step, is a tokenizer that extracts individual elements, such as "Tm",
		  "TJ", "/Rx" or "510.77". This is handled by the __next_token() method.
		- The second one, __next_instruction(), collects tokens. It pushes every floating-point value onto the
		  stack, until an instruction is met.
		- The third one, ExtractText(), processes data returned by __next_instruction(), and actually performs
		  the (restricted) parsing of text drawing instructions.

	 *-------------------------------------------------------------------------------------------------------------*/
	private function  ExtractText ( $object_id, $data, &$current_font )
	   {
		$new_data	=  $this -> __strip_useless_instructions ( $data ) ;

		if  ( self::$DEBUG )
		   {
			echo "\n----------------------------------- TEXT #$object_id (size = " . strlen ( $data ) . " bytes, new size = " . strlen ( $new_data ) . " bytes)\n" ;
			echo $data ;
			echo "\n----------------------------------- OPTIMIZED TEXT #$object_id\n" ;
			echo $new_data ;
		    }

		$data		=  $new_data ;

		// Index into the specified block of text-drawing instructions
		$data_index 			=  0 ;

		$data_length 			=  strlen ( $data ) ;		// Data length
		$result 			=  '' ;				// Resulting string

		// Y-coordinate of the last seen "Tm" instruction
		$last_goto_y 			=  0 ;
		$last_goto_x			=  0 ;

		// Y-coordinate of the last seen "Td" or "TD" relative positioning instruction
		$last_relative_goto_y		=  0 ;

		// When true, the current text should be output on the same line as the preceding one
		$use_same_line 			=  false ;

		// Instruction preceding the current one
		$last_instruction 		=  true ;

		// Current font size
		$current_font_size 		=  0 ;

		// Various pre-computed variables
		$separator_length		=  strlen ( $this -> Separator ) ;

		// Current font map width, in bytes, plus a flag saying whether the current font is mapped or not
		if  ( isset ( $this -> FontInformationBuffer [ $current_font ] ) )
		   {
			$current_font_map_width		=  $this -> FontInformationBuffer [ $current_font ] [ 'width' ] ;
			$current_font_mapped		=  $this -> FontInformationBuffer [ $current_font ] [ 'mapped' ] ;
		    }
		else
		   {
			$current_font_map_width		=  $this -> FontTable -> GetMapWidth ( $current_font ) ;
			$current_font_mapped		=  $this -> FontTable -> IsMapped ( $current_font ) ;

			$this -> FontInformationBuffer [ $current_font ]	=
			   [
				'width'		=>  $current_font_map_width,
				'mapped'	=>  $current_font_mapped
			    ] ;
		    }

		// Extra newlines to add before the current text
		$extra_newlines 		=  0 ;

		// Text leading used by T*
		$text_leading 			=  0 ;

		// Set to true if a separator needs to be inserted
		$needs_separator		=  false ;

		// A flag to tell if we should "forget" the last instruction
		$discard_last_instruction	=  false ;

		// A flag that tells whether the Separator and BlockSeparator properties are identical
		$same_separators		=  ( $this -> Separator  ==  $this -> BlockSeparator ) ;

		// Loop through instructions
		while  ( ( $instruction =  $this -> __next_instruction ( $data, $data_length, $data_index ) )  !==  false )
		   {
			// Character position after the current instruction
			$data_index 	=  $instruction [ 'next' ] ;

			// Process current instruction
			switch  ( $instruction [ 'instruction' ] )
			   {
				// "Tm", "Td" or "TD" : Output text on the same line, if the "y" coordinates are equal
			   	case 	'goto' :
					// Some text is positioned using 'Tm' instructions ; however they can be immediatley followed by 'Td' instructions
					// which give a relative positioning ; so consider that the last instruction wins
					if  ( $instruction [ 'relative' ] )
					   {
						// Try to put a separator if the x coordinate is non-zero
						//if  ( $instruction [ 'x' ] - $last_goto_x  >=  $current_font_size )
						//	$result		.=  $this -> Separator ;

						$discard_last_instruction	=  true ;
						$extra_newlines			=  0 ; 
						$use_same_line			=  ( ( $last_relative_goto_y - abs ( $instruction  [ 'y' ] ) )  <=  $current_font_size ) ;
						$last_relative_goto_y		=  abs ( $instruction [ 'y' ] ) ;
						$last_goto_x			=  $instruction [ 'x' ] ;
						
						if  ( - $instruction [ 'y' ]  >  $current_font_size ) 
						   {
							$use_same_line		=  false ;
							$extra_newlines		=  ( integer ) ( $current_font_size / $last_relative_goto_y ) ;
						    }
						else if  ( ! $instruction [ 'y' ] ) 
						   {
							$use_same_line		=  true ;
							$extra_newlines		=  0 ;
						    }
							
						break ;
					    }
					else
						$last_relative_goto_y	=  0 ;

					$y	=  $last_goto_y + $last_relative_goto_y ;

			   		if  ( $instruction [ 'y' ]  ==  $y  ||  abs ( $instruction [ 'y' ] - $y )  <  $current_font_size )
			   		   {
			   			$use_same_line 		=  true ;
			   			$extra_newlines 	=  0 ;
			   		    }
					else
					   {
					   	// Compute the number of newlines we have to insert between the current and the next lines
					   	if  ( $current_font_size ){
					   		if($current_font_size != 0){
								$extra_newlines =  ( integer ) ( ( $y - $instruction [ 'y' ] - $current_font_size ) / $current_font_size ) ;
							}else{
								$use_same_line 		=  true ;
			   					$extra_newlines 	=  0 ;
							}
						}
						$use_same_line 		=  ( $last_goto_y  ==  0 ) ;
					    }

					$last_goto_y 		=  $instruction [ 'y' ] ;
			   		break ;

				// "/Rx" : sets the current font
			   	case 	'resource' :
			   		$current_font 		=  $instruction [ 'resource' ] ;

					if  ( isset ( $this -> FontInformationBuffer [ $current_font ] ) )
					   {
						$current_font_map_width		=  $this -> FontInformationBuffer [ $current_font ] [ 'width' ] ;
						$current_font_mapped		=  $this -> FontInformationBuffer [ $current_font ] [ 'mapped' ] ;
					    }
					else
					   {
						$current_font_map_width		=  $this -> FontTable -> GetMapWidth ( $current_font ) ;
						$current_font_mapped		=  $this -> FontTable -> IsMapped ( $current_font ) ;

						$this -> FontInformationBuffer [ $current_font ]	=
						   [
							'width'		=>  $current_font_map_width,
							'mapped'	=>  $current_font_mapped
						    ] ;
					    }

			   		break ;

			   	case 	'fontsize' :
			   		$current_font_size 	=  $instruction [ 'size' ] ;
			   		break ;

			   	// 'TL' : text leading to be used for the next "T*" in the flow
			   	case 'leading' :
					if  ( ! ( $this -> Options & self::PDFOPT_IGNORE_TEXT_LEADING ) )
			   			$text_leading 		=  $instruction [ 'size' ] ;

			   		break ;

				// An "nl" instruction means TJ, Tj, T* or "'"
			   	case 	'nl' :
			   		if  ( ! $instruction [ 'conditional' ] )
			   		   {
			   		   	if  ( $instruction [ 'leading' ]  &&  $text_leading  &&  $current_font_size )
			   		   	   {
			   		   		$count 	=  ( integer ) ( ( $text_leading - $current_font_size ) / $current_font_size ) ;

			   		   		if  ( ! $count )
			   		   			$count 	=  1 ;
			   		   	    }
			   		   	else
			   		   		$count 	=  1 ;

		   		   		$extra			 =  str_repeat ( PHP_EOL, $count ) ;
			   			$result 		.=  $extra ;
						$needs_separator	 =  false ;
						$last_goto_y 		-=  ( $count * $text_leading ) ;	// Approximation on y-coord change
						$last_relative_goto_y	 =  0 ;
			   		    }

			   		break ;

				// Raw text (enclosed by parentheses) or array text (enclosed within square brackets)
				// is returned as a single instruction
			   	case 	'text' :
					// Empty arrays of text may be encountered - ignore them
					if  ( ! count ( $instruction [ 'values' ] ) )
						break ;

					// Check if we have to insert a newline
			   		if ( ! $use_same_line )
					   {
			   			$result 		.=  $this -> EOL ;
						$needs_separator	 =  false ;
					    }
			   		// Roughly simulate spacing between lines by inserting newline characters
			   		else if  ( $extra_newlines  > 0 )
			   		   {
			   			$result 		.=  str_repeat ( $this -> EOL, $extra_newlines ) ;
			   			$extra_newlines		 =  0 ;
						$needs_separator	 =  false ;
			   		    }
					else 
						$needs_separator	=  true ;

					// Add a separator if necessary
					if  ( $needs_separator )
					   {
						// If the Separator and BlockSeparator properties are the same (and not empty), only add a block separator if
						// the current result does not end with it
						if  ( $same_separators )
						   {
							if  ( $this -> Separator  !=  ''  &&  substr ( $result, - $separator_length )  !=  $this -> BlockSeparator )
								$result		.=  $this -> BlockSeparator ;
						    }
						else
							$result		.=  $this -> BlockSeparator ;
					    }

					$needs_separator	=  true ;
					$value_index		=  0 ;

					// Fonts having character maps will require some special processing
					if  ( $current_font_mapped )
					   {
					   	// Loop through each text value
			   			foreach  ( $instruction [ 'values' ]  as  $text )
			   			   {
			   		   		$is_hex 	=  ( $text [0]  ==  '<' ) ;
			   			   	$length 	=  strlen ( $text ) - 1 ;
							$handled	=  false ;

			   			   	// Characters are encoded within angle brackets ( "<>" ).
							// Note that several characters can be specified within the same angle brackets, so we have to take
							// into account the width we detected in the begincodespancerange construct 
			   			   	if  ( $is_hex )
			   			   	   {
			   			   	   	for  ( $i = 1 ; $i  <  $length ; $i += $current_font_map_width )
			   			   	   	   {
									$value		 =  substr ( $text, $i, $current_font_map_width ) ;
			   			   	   	   	$ch 		 =  hexdec ( $value ) ;
									$newchar	 =  $this -> FontTable -> MapCharacter ( $current_font, $ch ) ;
			   			   			$result		.=  $newchar ;
			   			   	   	    }

								$handled	 =  true ;
			   			   	    }
							// Yes ! double-byte codes can also be specified as plain text within parentheses !
							// However, we have to be really careful here ; the sequence :
							//	(Be)
							// can mean the string "Be" or the Unicode character 0x4265 ('B' = 0x42, 'e' = 0x65)
							// We first look if the character map contains an entry for Unicode codepoint 0x4265 ;
							// if not, then we have to consider that it is regular text to be taken one character by
							// one character. In this case, we fall back to the "if ( ! $handled )" condition
							else if  ( $current_font_map_width  ==  4  )
							   {
								$unknown_character_met	=  false ;
								$temp_result		=  '' ;

								for  ( $i = 1 ; $i  <  $length ; $i += 2 )
								   {
									$ch		=  ( ord ( $text [$i] )  <<  8 )  |  ord ( $text [ $i + 1 ] ) ;
									$newchar	=  $this -> FontTable -> MapCharacter ( $current_font, $ch, true ) ;

									// No mapping found for this pair of characters ; process them individually
									if  ( $newchar  ===  false )
									   {
										$unknown_character_met	=  true ;
										break ;
									    }

									$temp_result		.=  $newchar ;
								    }

								if  ( ! $unknown_character_met )
								   {
									$result		.=  $temp_result ;
									$handled	 =  true ;
								    }
							    }

							// Character strings within parentheses.
							// For every text value, use the character map table for substitutions
							if  ( ! $handled )
							   {
				   		   		for  ( $i = 1 ; $i  <  $length ; $i ++ )
				   		   		   {
				   		   			$ch 	=  $text [$i] ;

									// ... but don't forget to handle escape sequences "\n" and "\r" for characters
									// 10 and 13
				   		   			if  ( $ch  ==  '\\' )
				   		   			   {
				   		   				$ch 	=  $text [++$i] ;

				   		   				switch  ( $ch )
				   		   				   {
				   		   					case 	'n' 	:  $ch =  "\n" ; break ;
				   		   					case 	'r' 	:  $ch =  "\r" ; break ;

											// However, an octal form can also be specified ; in this case we have to take into account
											// the character width for the current font (if the character width is 4 hex digits, then we
											// will encounter constructs such as "\000\077").
											// The method used here is dirty : we build a regex to match octal character representations on a substring
											// of the text 
											default :
												$width		=  $current_font_map_width / 2 ;	// Convert to byte count
												$subtext	=  substr ( $text, $i - 1 ) ;
												$regex		=  "#^ (\\\\ [0-7]+){1,$width} #imsx" ;

												$status		=  preg_match ( $regex, $subtext, $octal_matches ) ;

												if  ( $status )
												   {
													$octal_values	=  explode ( '\\', substr ( $octal_matches [0], 1 ) ) ;
													$ord		=  0 ;

													foreach  ( $octal_values  as  $octal_value ) 
														$ord	=  ( $ord  <<  8 ) + octdec ( $octal_value ) ;

													$ch	 =  chr ( $ord ) ;
													$i	+=  strlen ( $octal_matches [0] ) - 2 ;
												    }
				   		   				    }
				   		   			    }

									// Add substituted character to the output result
									$newchar	 =  $this -> FontTable -> MapCharacter ( $current_font, ord ( $ch ) ) ;
									$result		.=  $newchar ;
				   		   		    }
							    }

							// Handle offsets between blocks of characters
							if  ( isset ( $instruction [ 'offsets' ] [ $value_index ] )  &&
									- ( $instruction [ 'offsets' ] [ $value_index ] )  >  $this -> MinSpaceWidth )
								$result		.=  $this -> __get_character_padding ( $instruction [ 'offsets' ] [ $value_index ] ) ;

							$value_index ++ ;
			   		   	    }
			   		    }
					// For fonts having no associated character map, we simply encode the string in UTF8
					// after the C-like escape sequences have been processed
					// Note that <xxxx> constructs can be encountered here, so we have to process them as well
			   		else
			   		   {
			   			foreach  ( $instruction [ 'values' ]  as  $text )
			   			   {
			   			   	$is_hex 	=  ( $text [0]  ==  '<' ) ;
			   			   	$length 	=  strlen ( $text ) - 1 ;

							// Some text within parentheses may have a backslash followed by a newline, to indicate some continuation line.
							// Example :
							//	(this is a sentence \
							//	 continued on the next line)
							// Funny isn't it ? so remove such constructs because we don't care
							$text		=  str_replace ( [ "\\\r\n", "\\\r", "\\\n" ], '', $text ) ;

			   			   	// Characters are encoded within angle brackets ( "<>" )
			   			   	if  ( $is_hex )
			   			   	   {
			   			   	   	for  ( $i = 1 ; $i  <  $length ; $i += 2 )
			   			   	   	   {
			   			   	   	   	$ch 	=  hexdec ( substr ( $text, $i, 2 ) ) ;

			   			   			$result .=  $this -> CodePointToUtf8 ( $ch ) ;
			   			   	   	    }
			   			   	    }
							// Characters are plain text
			   			   	else
							   {
								$text	=  $this -> Unescape ( $text ) ;

								for  ( $i = 1, $length = strlen ( $text ) - 1 ; $i  <  $length ; $i ++ )
								   {
									$ch	=  $text [$i] ;

									if  ( ord ( $ch )  <  127 )
										$newchar	=  $ch ;
									else
										$newchar	=  $this -> FontTable -> MapCharacter ( $current_font, ord ( $ch ) ) ;

									$result		.=  $newchar ;
								    }
							    }

							// Handle offsets between blocks of characters
							if  ( isset ( $instruction [ 'offsets' ] [ $value_index ] )  &&
									abs ( $instruction [ 'offsets' ] [ $value_index ] )  >  $this -> MinSpaceWidth )
								$result		.=  $this -> __get_character_padding ( $instruction [ 'offsets' ] [ $value_index ] ) ;

							$value_index ++ ;
			   			   }
			   		    }
			    }

			// Remember last instruction - this will help us into determining whether we should put the next text
			// on the current or following line
			if  ( ! $discard_last_instruction )
				$last_instruction 	=  $instruction ;

			$discard_last_instruction	=  false ;
		    }

		return ( $this -> __rtl_process ( $result ) ) ;
	    }


	// __next_instruction -
	//	Retrieves the next instruction from the drawing text block.
	function  __next_instruction ( $data, $data_length, $index )
	   {
		static 	$last_instruction 	=  false ;

		$ch	=  '' ;

		// Constructs such as
		if  ( $last_instruction )
		   {
			$result 		=  $last_instruction ;
			$last_instruction	=  false ;

			return ( $result ) ;
		    }

		// Holds the floating-point values encountered so far
		$number_stack 	=  [] ;

		// Loop through the stream of tokens
		while  ( ( $part = $this -> __next_token ( $data, $data_length, $index ) )  !==  false )
		   {
			$token 		=  $part [0] ;
			$next_index 	=  $part [1] ;

			// Floating-point number : push it onto the stack
			if  ( ( $token [0]  >=  '0'  &&  $token [0]  <=  '9' )  ||  $token [0]  ==  '-'  ||  $token [0]  ==  '+'  ||  $token [0]  ==  '.' )
				$number_stack []	=  $token ;
			// 'Tm' instruction : return a "goto" instruction with the x and y coordinates
			else if  ( $token  ==  'Tm' )
			   {
				$x 	=  $number_stack [4] ;
				$y 	=  $number_stack [5] ;

				return ( [ 'instruction' => 'goto', 'next' => $next_index, 'x' => $x, 'y' => $y, 'relative' => false, 'token' => $token ] ) ;
			    }
			// 'Td' or 'TD' instructions : return a goto instruction with the x and y coordinates (1st and 2nd args)
			else if  ( $token  ==  'Td'  ||  $token  ==  'TD' )
			   {
				$x 	=  $number_stack [0] ;
				$y 	=  $number_stack [1] ;

				return ( [ 'instruction' => 'goto', 'next' => $next_index, 'x' => $x, 'y' => $y, 'relative' => true, 'token' => $token ] ) ;
			    }
			// Output text "'" instruction, with conditional newline
			else if  ( $token [0]  ==  "'" )
				return ( [ 'instruction' => 'nl', 'next' => $next_index, 'conditional' => true, 'leading' => false, 'token' => $token ] ) ;
			// Same as above
			else if  ( $token  ==  'TJ'  ||  $token  ==  'Tj' )
				return ( [ 'instruction' => 'nl', 'next' => $next_index, 'conditional' => true, 'leading' => false, 'token' => $token ] ) ;
			// Set font size
			else if  ( $token  ==  'Tf' )
				return ( [ 'instruction' => 'fontsize', 'next' => $next_index, 'size' => $number_stack [0], 'token' => $token ] ) ;
			// Text leading (spacing used by T*)
			else if  ( $token  ==  'TL' )
				return ( [ 'instruction' => 'leading', 'next' => $next_index, 'size' => $number_stack [0], 'token' => $token ] ) ;
			// Position to next line
			else if  ( $token  ==  'T*' )
				return ( [ 'instruction' => 'nl', 'next' => $next_index, 'conditional' => false, 'leading' => true ] ) ;
			// Draw object ("Do"). To prevent different text shapes to appear on the same line, we return a "newline" instruction
			// here. Note that the shape position is not taken into account here, and shapes will be processed in the order they
			// appear in the pdf file (which is likely to be different from their position on a graphic screen).
			else if  ( $token  ==  'Do' )
				return ( [ 'instruction' => 'nl', 'next' => $next_index, 'conditional' => false, 'leading' => false, 'token' => $token ] ) ;
			// Raw text output
			else if  ( $token [0]  ==  '(' )
			   {
			   	$next_part 	=  $this -> __next_token ( $data, $data_length, $next_index ) ;
			   	$instruction	=  [ 'instruction' => 'text', 'next' => $next_index, 'values' => [ $token ], 'token' => $token ] ;

			   	if  ( $next_part [0]  ==  "'" )
			   	   {
			   	   	$last_instruction  	=  $instruction ;
			   	   	return ( [ 'instruction' => 'nl', 'next' => $next_index, 'conditional' => false, 'leading' => true, 'token' => $token ] ) ;
			   	   }
			   	else
					return ( $instruction ) ;
			    }
		   	else if  ( $token [0]  ==  '<'  )
			   {
				$ch	=  $token [1] ;

				if  ( isset ( self::$CharacterClass [ $ch ] )  &&  ( self::$CharacterClass & self::CTYPE_ALNUM ) )
				   {
			   		$next_part 	=  $this -> __next_token ( $data, $data_length, $next_index ) ;
			   		$instruction	=  [ 'instruction' => 'text', 'next' => $next_index, 'values' => [ $token ], 'token' => $token ] ;

			   		if  ( $next_part [0]  ==  "'" )
			   		   {
			   	   		$last_instruction  	=  $instruction ;
			   	   		return ( [ 'instruction' => 'nl', 'next' => $next_index, 'conditional' => false, 'leading' => true, 'token' => $token ] ) ;
			   		   }
			   		else
						return ( $instruction ) ;
				    }
			    }
			    // Text specified as an array of individual raw text elements, and individual interspaces between characters
			else if  ( $token [0]  ==  '[' )
			   {
				$values 	=  $this -> __extract_chars_from_array ( $token ) ;
				$instruction 	=  [ 'instruction' => 'text', 'next' => $next_index, 'values' => $values [0], 'offsets' => $values [1], 'token' => $token ] ;

				return ( $instruction ) ;
			    }
			// Token starts with a slash : maybe a font specification
			else if  ( $token [0]  ==  '/'  &&  isset ( $token [1] )  &&  isset ( $token [2] ) )
			   {
				$ch	=  $token [1] ;

				switch  ( $ch )
				   {
					// Font reference : "/Fx", "/fx-y", "/TTx", "/Tx", "/R"
					case	'R' :  case  'r' :
					case	'T' :  case  't' :
					case	'F' :  case  'f' :
					case    'C' :  case  'c' :
						if  ( isset ( $this -> MapIdBuffer [ $token ] ) )
							$id	=   $this -> MapIdBuffer [ $token ] ;
						else
						   {
							$id 	=  $this -> FontTable -> GetFontByMapId ( $token ) ;

							$this -> MapIdBuffer [ $token ]	=  $id ;
						    }

						return ( [ 'instruction' => 'resource', 'next' => $next_index, 'resource' => $id, 'token' => $token ] ) ;

					default :
						$number_stack	=  [] ;
				    }
			    }
			    // Other instructions : we're not that much interested in them, so clear the number stack and consider
			// that the current parameters, floating-point values, have been processed
			else
				$number_stack 	=  [] ;

			$index 		=  $next_index ;
		    }

		// End of input
		return ( false ) ;
	    }


	// __next_token :
	//	Retrieves the next token from the drawing instructions stream.
	function  __next_token ( $data, $data_length, $index )
	   {
		// Skip spaces
		while  ( $index  <  $data_length  &&  ( $data [ $index ]  ==  ' '  ||  $data [ $index ]  ==  "\t"  ||  $data [ $index ]  ==  "\r"  ||  $data [ $index ]  ==  "\n" ) )
			$index ++ ;

		// End of input
		if  ( $index  >=  $data_length )
			return ( false ) ;

		// The current character will tell us what to do
		$ch 	=  $data [ $index ] ;
		$ch2	=  '' ;

		switch ( $ch )
		   {
			// Opening square bracket : we have to find the closing one, taking care of escape sequences
			// that can also specify a square bracket, such as "\]"
		   	case 	"[" :
		   		$pos 		=  $index + 1 ;
		   		$parent 	=  0 ;
		   		$angle 	=  0 ;
		   		$result		=  $ch ;

		   		while  ( $pos  <  $data_length )
		   		   {
		   			$nch 	=  $data [ $pos ++ ] ;

		   			switch  ( $nch )
		   			   {
		   			   	case 	'(' :
		   			   		$parent ++ ;
		   			   		$result 	.=  $nch ;
		   			   		break ;

		   			   	case 	')' :
		   			   		$parent -- ;
		   			   		$result 	.=  $nch ;
		   			   		break ;

		   			   	case 	'<' :
		   			   		$angle ++ ;
		   			   		$result 	.=  $nch ;
		   			   		break ;

		   			   	case 	'>' :
		   			   		$angle -- ;
		   			   		$result 	.=  $nch ;
		   			   		break ;

		   			   	case 	'\\' :
		   					$result 	.=  $nch . $data [ $pos ++ ] ;
		   					break ;

		   			   	case 	']' :
		   					$result 	.=  ']' ;

		   					if  ( ! $parent  ||  ! $angle )
		   						break  2 ;
		   					else
		   						break ;

						case	"\n" :
						case	"\r" :
							break ;

		   			   	default :
		   			   		$result 	.=  $nch ;
		   			    }
		   		    }

		   		return ( [ $result, $pos ] ) ;

			// Parenthesis : Again, we have to find the closing parenthesis, taking care of escape sequences
			// such as "\)"
		   	case 	"(" :
		   		$pos 		=  $index + 1 ;
		   		$result		=  $ch ;

		   		while  ( $pos  <  $data_length )
		   		   {
		   			$nch 	=  $data [ $pos ++ ] ;

		   			if  ( $nch  ==  '\\' )
					   {
						$after		 =  $data [ $pos ] ;

						// Character references specified as \xyz, where "xyz" are octal digits
						if  ( $after  >=  '0'  &&  $after  <=  '7' )
						   {
							$result		.=  $nch ;

							while  ( $data [ $pos ]  >=  '0'  &&  $data [ $pos ]  <=  '7' )
								$result		.=  $data [ $pos ++ ] ;
						    }
						// Regular character escapes
						else
		   					$result 	.=  $nch . $data [ $pos ++ ] ;
					    }
		   			else if  ( $nch  ==  ')' )
		   			   {
		   				$result 	.=  ')' ;
		   				break ;
		   			    }
		   			else
		   				$result 	.=  $nch ;
		   		   }

		   		return ( [ $result, $pos ] ) ;

			// A construction of the form : "<< something >>", or a unicode character
		   	case 	'<' :
				if  ( ! isset ( $data [ $index + 1 ] ) )
					return ( false ) ;

		   		if (  $data [ $index + 1 ]  ==  '<' )
		   		   {
		   		   	$pos 	=  strpos ( $data, '>>', $index + 2 ) ;

		   			if  ( $pos  ===  false )
		   				return ( false ) ;

		   			return ( [ substr ( $data, $index, $pos - $index + 2 ), $pos + 2 ] ) ;
		   		    }
		   		else
		   		   {
		   		   	$pos 	=  strpos ( $data, '>', $index + 2 ) ;

		   			if  ( $pos  ===  false )
		   				return ( false ) ;

		   			return ( [ substr ( $data, $index, $pos - $index + 1 ), $pos + 1 ] ) ;
		   		   }

			// Tick character : consider it as a keyword, in the same way as the "TJ" or "Tj" keywords
		   	case 	"'" :
		   		return ( [ "'", $index + 1 ] ) ;

			// Other cases : this may be either a floating-point number or a keyword
		   	default :
		   		$index ++ ;
		   		$value 	=  $ch ;

				if  ( isset ( $data [ $index ] ) )
				   {
		   			if ( ( isset ( self::$CharacterClass [ $ch ]  ) &&  ( self::$CharacterClass [ $ch ] & self::CTYPE_DIGIT ) )  ||  
							$ch  ==  '-'  ||  $ch  ==  '+' )
		   			   {
		   				while  ( $index  <  $data_length  &&
		   						( ( isset ( self::$CharacterClass [ $data [ $index ] ]  ) &&  ( self::$CharacterClass [ $data [ $index ] ] & self::CTYPE_DIGIT )  ||  
									$data [ $index ]  ==  '.' ) ) )
		   					$value 	.=  $data [ $index ++ ] ;
		   			    }
		   			else if  ( ( isset ( self::$CharacterClass [ $ch ] )  &&  ( self::$CharacterClass [ $ch ] & self::CTYPE_ALPHA ) )  ||  
							$ch  ==  '/' )
		   			   {
						$ch	=  $data [ $index ] ;

						while  ( $index  <  $data_length  &&  
							( ( isset ( self::$CharacterClass [ $ch ] )  &&  ( self::$CharacterClass [ $ch ] & self::CTYPE_ALNUM ) )  ||  
								$ch  ==  '*'  ||  $ch  ==  '-'  ||  $ch  ==  '_' ) )
						   {
							$value 	.=  $ch ;
							$index ++ ;

							if  ( isset ( $data [ $index ] ) )
								$ch	=  $data [ $index ] ;
						    }
		   			    }
				    }

		   		return ( [ $value, $index ] ) ;
		    }
	    }



	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        GetTrailerInformation - Retrieves trailer information.
	
	    PROTOTYPE
	        $this -> GetTrailerInformation ( $contents ) ;
	
	    DESCRIPTION
	        Retrieves trailer information :
		- Unique file ID
		- Id of the object containing encryption data, if the PDF file is encrypted
		- Encryption data
	
	    PARAMETERS
	        $contents (string) -
	                PDF file contents.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  GetTrailerInformation ( $contents )
	   {
		// Be paranoid : check if there is trailer information
		if  ( ! preg_match ( '/trailer \s* << (?P<trailer> .+?) >>/imsx', $contents, $trailer_match ) )
			return ;

		$trailer_data	=  $trailer_match [ 'trailer' ] ;

		// Get the unique file id from the trailer data
		static		$id_regex	=  '#
							/ID \s* \[ \s*
							< (?P<id1> [^>]+) >
							\s*
							< (?P<id2> [^>]+) >
							\s* \]
						    #imsx' ;

		if  ( preg_match ( $id_regex, $trailer_data, $id_match ) )
		   {
			$this -> ID	=  $id_match [ 'id1' ] ;
			$this -> ID2	=  $id_match [ 'id2' ] ;
		    }

		// If there is an object describing encryption data, get its number (/Encrypt flag)
		if (  ! preg_match ( '#/Encrypt \s+ (?P<object> \d+)#ix', $trailer_data, $encrypt_match ) )
			return ;

		$encrypt_object_id	=  $encrypt_match [ 'object' ] ;

		// Retrieve the encryption dictionary contents
		if  ( ! preg_match ( '#' . $encrypt_object_id . ' \s+ 0 \s+ obj (?P<encrypt> .*?) \s* endobj#imsx', $contents, $encryption_match ) )
			return ;

		$encryption_data	=  $encryption_match [ 'encrypt' ] ;
		
		// Encryption mode
		if  ( ! preg_match ( '#/Filter \s* / (?P<mode> \w+)#ix', $encryption_data, $encryption_data_match ) )
			return ;

		switch ( strtolower ( $encryption_data_match [ 'mode' ] ) )
		   {
			case	'standard' :
				$this -> EncryptionMode		=  self::PDFCRYPT_STANDARD ;
				break ;

			default :
				if  ( self::$DEBUG  >  1 )
					error ( new PdfToTextException ( "Unhandled encryption mode '{$encryption_data [ 'mode' ]}'", $encrypt_object_id ) ) ;
				
		    }

		// Other encryption data
		preg_match ( '#/V \s+ (?P<value> \d+)#ix', $encryption_data, $algorithm_match ) ;
		$this -> EncryptionAlgorithm		=  ( integer ) $algorithm_match [ 'value' ] ;

		preg_match ( '#/R \s+ (?P<value> \d+)#ix', $encryption_data, $algorithm_revision_match ) ;
		$this -> EncryptionAlgorithmRevision	=  ( integer ) $algorithm_revision_match [ 'value' ] ;

		preg_match ( '#/P \s+ (?P<value> \-? \d+)#ix', $encryption_data, $flags_match ) ;
		$this -> EncryptionFlags		=  ( integer) $flags_match [ 'value' ] ;

		// Key length (40 bits, if not specified)
		if  ( preg_match ( '#/Length \s+ (?P<value> \d+)#ix', $encryption_data, $key_length_match ) )
			$this -> EncryptionKeyLength		=  $key_length_match [ 'value' ] ;
		else 
			$this -> EncryptionKeyLength		=  40 ;

		// Hashed user and owner passwords
		preg_match ( '#/U \s* \( \s* (?P<value> [^)]+) \)#ix', $encryption_data, $user_match ) ;
		$this -> UserEncryptionKey		=  $user_match [ 'value' ] ;

		preg_match ( '#/O \s* \( \s* (?P<value> [^)]+) \)#ix', $encryption_data, $owner_match ) ;
		$this -> OwnerEncryptionKey		=  $owner_match [ 'value' ] ;

		// EncryptMetadata flag
		if  ( preg_match ( '# /EncryptMetadata (?P<value> (true) | (1) | (false) | (0) )#imsx', $encryption_data, $encryption_match ) )
		   {
			if  ( ! strcasecmp ( $encryption_match [ 'value' ], 'true' )  ||  ! strcasecmp ( $encryption_match [ 'value' ], 'false' ) )
				$this -> EncrypMetadata		=  true ;
			else
				$this -> EncrypMetadata		=  false ;
		    }
		else
			$this -> EncryptMetadata	=  false ;

		// Say that the file is password-protected
		$this -> IsPasswordProtected		=  true ;

		// Paranoia : owner password defaults to user password
		if  ( ! $this -> OwnerPassword )
			$this -> OwnerPassword		=  $this -> UserPassword ;

		// Generate encryption key
		$this -> GenerateEncryptionKey ( ) ;
	    }


	protected function  GenerateEncryptionKey ( ) 
	   {
		
		static	$ImplementedAlgorithms		=
		   [
			'2'	=>
			   [
				//'2'	=>  [ 'self', '__generate_encryption_key_v2_r23' ],
				'3'	=>  [ 'self', '__generate_encryption_key_v2_r23' ],
			    ]
		    ] ;

		if  ( isset ( $ImplementedAlgorithms [ $this -> EncryptionAlgorithm ] [ $this -> EncryptionAlgorithmRevision ] ) )
		   {
			$callback	=  $ImplementedAlgorithms [ $this -> EncryptionAlgorithm ] [ $this -> EncryptionAlgorithmRevision ] ;
			$key		=  call_user_func ( $callback ) ;
		    }
		else
		   {
			if  ( self::$DEBUG  >  1 )
				error ( "Encryption algorithm version {$this -> EncryptionAlgorithm} revision {$this -> EncryptionAlgorithmRevision} is not yet implemented." ) ;
			else
				$key	=  false ;
		    }

		$this -> EncryptionKey	=  $key ;
	    }


	private function  __generate_encryption_key_v2_r23 ( )
	   {
		$key_byte_length	=  $this -> EncryptionKeyLength / 8 ;

		// If no user password has been defined, use the padding string as the start of the value to be hashed
		if (  $this -> UserPassword )
			$buffer		=  substr ( $this -> UserPassword . self::PDF_ENCRYPTION_PADDING, 0, 32 ) ;
		else
			$buffer		=  self::PDF_ENCRYPTION_PADDING ;

		// Add the owner password encryption key
		$buffer		.=  $this -> OwnerEncryptionKey ;

		// Add the permission flags, considered as a 32-bit integer, LSB first
		$buffer		.=  chr ( ( $this -> EncryptionFlags         ) & 0xFF ) .
				    chr ( ( $this -> EncryptionFlags  >>   8 ) & 0xFF ) .
				    chr ( ( $this -> EncryptionFlags  >>  16 ) & 0xFF ) .
				    chr ( ( $this -> EncryptionFlags  >>  24 ) & 0xFF ) ;

		// Append the first file id
		$buffer		.=  $this -> ID ;

		// If metadata is not encrypted, append four 0xFF bytes
		if  ( ! $this -> EncryptMetadata )
			$buffer		.=  "\xFF\xFF\xFF\xFF" ;

		// That was the first step ; now we need to compute the md5 hash of this stuff
		$md5_hash	=  md5 ( $buffer, true ) ;

		// For enryption alogrithm 2 revision 3, we need to iterate 50 times over the same hash
		if  ( $this -> EncryptionAlgorithmRevision  ==  '3' )
		   {
			for  ( $i = 0 ; $i  <  50 ; $i ++ )
				$md5_hash	=  md5 ( substr ( $md5_hash, 0, $key_byte_length ), true ) ;
		    }

		// Now verify that the generated encryption key matches the user key found in the pdf file
		if  ( $this -> EncryptionAlgorithmRevision  ==  '3' )
		   {
			$salt		=  md5 ( self::PDF_ENCRYPTION_PADDING . $this -> ID, true ) ;
			$salt_length	=  strlen ( $salt ) ;
			$encrypted	=  mcrypt_encrypt ( MCRYPT_ARCFOUR, $md5_hash, $salt, MCRYPT_MODE_STREAM, '') ;

			for  ( $i = 1 ; $i  <=  19 ; $i ++ )
			   {
				$tmp_key	=  '' ;

				for  ( $j = 0 ; $j  <  $salt_length ; $j ++ )
					$tmp_key	.=  chr ( ord ( $md5_hash [$j] ) ^ $i ) ;

				$encrypted	=  mcrypt_encrypt ( MCRYPT_ARCFOUR, $tmp_key, $encrypted, MCRYPT_MODE_STREAM, '' ) ;
			    }

			dump ( $encrypted ) ;
			dump ( md5 ( $salt, true ) );
			dump ( $this -> UserEncryptionKey ) ;
			dump ( $this -> OwnerEncryptionKey ) ;
			exit ;
		    }

		// All done, return
		return ( $md5_hash ) ;
	    }	

/*
			$tmp = TCPDF_STATIC::_md5_16(TCPDF_STATIC::$enc_padding.$this->encryptdata['fileid']);
			$enc = TCPDF_STATIC::_RC4($this->encryptdata['key'], $tmp, $this->last_enc_key, $this->last_enc_key_c);
			$len = strlen($tmp);
			for ($i = 1; $i <= 19; ++$i) {
				$ek = '';
				for ($j = 0; $j < $len; ++$j) {
					$ek .= chr(ord($this->encryptdata['key'][$j]) ^ $i);
				}
			}
			echo "   " ; dump ( $enc);
			return $enc;
		} elseif ($this->encryptdata['mode'] == 3) { // AES-256
			$seed = TCPDF_STATIC::_md5_16(TCPDF_STATIC::getRandomSeed());
			// Owner Validation Salt
			$this->encryptdata['OVS'] = substr($seed, 0, 8);
			// Owner Key Salt
			$this->encryptdata['OKS'] = substr($seed, 8, 16);
			return hash('sha256', $this->encryptdata['owner_password'].$this->encryptdata['OVS'].$this->encryptdata['U'], true).$this->encryptdata['OVS'].$this->encryptdata['OKS'];
		}
	}
	protected function _Ovalue_ori() {
		if ($this->encryptdata['mode'] < 3) { // RC4-40, RC4-128, AES-128
			$tmp = TCPDF_STATIC::_md5_16($this->encryptdata['owner_password']);
			if ($this->encryptdata['mode'] > 0) {
				for ($i = 0; $i < 50; ++$i) {
					$tmp = TCPDF_STATIC::_md5_16($tmp);
				}
			}
			$owner_key = substr($tmp, 0, ($this->encryptdata['Length'] / 8));
			$enc = TCPDF_STATIC::_RC4($owner_key, $this->encryptdata['user_password'], $this->last_enc_key, $this->last_enc_key_c);
			if ($this->encryptdata['mode'] > 0) {
				$len = strlen($owner_key);
				for ($i = 1; $i <= 19; ++$i) {
					$ek = '';
					for ($j = 0; $j < $len; ++$j) {
						$ek .= chr(ord($owner_key[$j]) ^ $i);
					}
					$enc = TCPDF_STATIC::_RC4($ek, $enc, $this->last_enc_key, $this->last_enc_key_c);
				}
			}
			return $enc;
		} elseif ($this->encryptdata['mode'] == 3) { // AES-256
			$seed = TCPDF_STATIC::_md5_16(TCPDF_STATIC::getRandomSeed());
			// Owner Validation Salt
			$this->encryptdata['OVS'] = substr($seed, 0, 8);
			// Owner Key Salt
			$this->encryptdata['OKS'] = substr($seed, 8, 16);
			return hash('sha256', $this->encryptdata['owner_password'].$this->encryptdata['OVS'].$this->encryptdata['U'], true).$this->encryptdata['OVS'].$this->encryptdata['OKS'];
		}
	}

	public function _md5_16($str) {
		return pack('H*', md5($str));
	}
	public function _RC4($key, $text, &$last_enc_key, &$last_enc_key_c) {
		if (function_exists('mcrypt_encrypt') AND ($out = @mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_STREAM, ''))) {
			// try to use mcrypt function if exist
			return $out;
		}
		echo "HERE\n" ;
		if ($last_enc_key != $key) {
			$k = str_repeat($key, ((256 / strlen($key)) + 1));
			$rc4 = range(0, 255);
			$j = 0;
			for ($i = 0; $i < 256; ++$i) {
				$t = $rc4[$i];
				$j = ($j + $t + ord($k[$i])) % 256;
				$rc4[$i] = $rc4[$j];
				$rc4[$j] = $t;
			}
			$last_enc_key = $key;
			$last_enc_key_c = $rc4;
		} else {
			$rc4 = $last_enc_key_c;
		}
		$len = strlen($text);
		$a = 0;
		$b = 0;
		$out = '';
		for ($i = 0; $i < $len; ++$i) {
			$a = ($a + 1) % 256;
			$t = $rc4[$a];
			$b = ($b + $t) % 256;
			$rc4[$a] = $rc4[$b];
			$rc4[$b] = $t;
			$k = $rc4[($rc4[$a] + $rc4[$b]) % 256];
			$out .= chr(ord($text[$i]) ^ $k);
		}
		return $out;
	}



	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        IsRtlCharacter - Checks if a Unicode codepoint belongs to an RTL language.
	
	    PROTOTYPE
	        $status		=  $this -> IsRtlCharacter ( $ch ) ;
	
	    DESCRIPTION
	        Checks if a Unicode codepoint belongs to an RTL language.
		For performance reasons, this function operates on as few character ranges as possible. For this reason, 
		it may return true even if the specified codepoint does not have any character mapped to it.
	
	    PARAMETERS
	        $ch (integer) -
	                Unicode codepoint to be checked.
	
	    RETURN VALUE
	        Returns true if the specified Unicode codepoint belongs to an RTL language, false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  IsRtlCharacter ( $ch ) 
	   {
		if  ( isset ( $this -> RtlCharacterBuffer [ $ch ] ) )
			return ( $this -> RtlCharacterBuffer [ $ch ] ) ;
		else
		   {
			foreach  ( self::$RtlCharacters  as  $range )
			   {
				if  ( $ch  >=  $range [0]  &&  $ch  <=  $range [1] )
				   {
					$this -> RtlCharacterBuffer [ $ch ]	=  true ;

					return ( true ) ;
				    }
			    }

			$this -> RtlCharacterBuffer [ $ch ]	=  false ;

			return ( false ) ;
		    }
	    }


	// __build_ignored_instructions :
	//	Takes the template regular expressions from the self::$IgnoredInstructionsTemplates, replace each string with the contents
	//	of the self::$ReplacementConstructs array, and sets the self::$IgnoredInstructions to a regular expression that is able to
	//	match the Postscript instructions to be removed from any text stream.
	private function  __build_ignored_instructions ( )
	   {
		foreach  ( self::$IgnoredInstructionsTemplates  as  $template )
		   {
			$template	=  '/' .
						str_replace ( array_keys ( self::$ReplacementConstructs ), array_values ( self::$ReplacementConstructs ), $template ) .
					   '/msx' ;

			self::$IgnoredInstructions []	=  $template ;
		    }
	    }


	// __extract_chars_from_array -
	//	Extracts characters enclosed either within parentheses (character codes) or angle brackets (hex value)
	//	from an array.
	//	Example :
	//
	//		[<0D>-40<02>-36<03>-39<0E>-36<0F>-36<0B>-37<10>-37<10>-35(abc)]
	//
	// 	will return an array having the following entries :
	//
	//		<0D>, <02>, <03>, <0E>, <0F>, <0B>, <10>, <10>, (abc)
	private function  __extract_chars_from_array ( $array )
	   {
		$length 	=  strlen ( $array ) - 1 ;
		$result 	=  [] ;
		$offsets	=  [] ; 

		for  ( $i = 1 ; $i  <  $length ; $i ++ )	// Start with character right after the opening bracket
		   {
		   	$ch 	=  $array [$i] ;

			if  ( $ch  ==  '(' )
				$endch 	=  ')' ;
			else if  ( $ch  ==  '<' )
				$endch 	=  '>' ;
			else
			   {
				$value	=  '' ;

				while  ( $i  <  $length  &&  ( ( $array [$i]  >=  '0'  &&  $array [$i]  <=  '9' )  ||  $array [$i]  ==  '-'  ||  $array [$i]  ==  '+' ) )
					$value	.=  $array [$i++] ;

				$offsets []	=  ( integer ) $value ;

				if  ( $value  !==  '' )
					$i -- ;

				continue ;
			    }

			$char 	=  $ch ;
			$i ++ ;

			while  ( $i  <  $length  &&  $array [$i]  !=  $endch )
			   {
			   	if  ( $array [$i]  ==  '\\' )
			   		$char 	.=  '\\' . $array [++$i] ;
				else
				   {
					$char 	.=  $array [$i] ;

					if  ( $array [$i]  ==  $endch )
						break ;
				    }

				$i ++ ;
			   }

			$result [] 	 =  $char . $endch ;
		    }

		return ( [ $result, $offsets ] ) ;
	    }


	// __extract_chars_from_block -
	//	Extracts characters from a text block (enclosed in parentheses).
	//	Returns an array of character ordinals if the $as_array parameter is true, or a string if false.
	private function  __extract_chars_from_block ( $text, $start_index = false, $length = false, $as_array = false )
	   {
		if  ( $as_array ) 
			$result		=  [] ;
		else
			$result		=  '' ;

		if  ( $start_index  ===  false )
			$start_index	=  0 ;

		if  ( $length  ===  false )
			$length		=  strlen ( $text ) ;

		$ord0	=  ord ( '0' ) ;

		for  ( $i = $start_index ; $i  <  $length ; $i ++ )
		   {
			$ch	=  $text [$i] ;

			if  ( $ch  ==  '\\' )
			   {
				if  ( isset ( $text [ $i + 1 ] ) )
				   {
					$ch2	=  $text [ ++$i ] ;

					switch  ( $ch2 )
					   {
						case  'n' :  $ch =  "\n" ; break ;
						case  'r' :  $ch =  "\r" ; break ;
						case  't' :  $ch =  "\t" ; break ;
						case  'f' :  $ch =  "\f" ; break ;
						case  'v' :  $ch =  "\v" ; break ;

						default :
							if  ( $ch2  >=  '0'  &&  $ch2  <=  '7' )
							   {
								$ord	=  $ch2 - $ord0 ;
								$i ++ ;

								while  ( isset ( $text [$i] )  &&  $text [$i]  >=  '0'  &&  $text [$i]  <=  '7' )
								   {
									$ord	=  ( $ord * 8 ) + ord ( $text [$i] ) - $ord0 ;
									$i ++ ;
								    }

								$ch	=  chr ( $ord ) ;
								$i -- ;
							    }
							else
								$ch	=  $ch2 ;

					    }
				    }
			    }

			if  ( $as_array )
				$result []	 =  ord ( $ch ) ;
			else
				$result		.=  $ch ;
		    }

		return ( $result ) ;
	    }


	// __get_character_padding :
	//	If the offset specified between two character groups in an array notation for displaying text is less
	//	than -MinSpaceWidth thousands of text units, 
	private function  __get_character_padding ( $char_offset )
	   {
		if  ( $char_offset  <=  - $this -> MinSpaceWidth )
		   {
			if  ( $this -> Options  &&  self::PDFOPT_REPEAT_SEPARATOR )
			   {
				// If the MinSpaceWidth property is less than 1000 (text units), consider it has the value 1000
				// so that an exuberant number of spaces will not be repeated
				$space_width	=  ( $this -> MinSpaceWidth  <  1000 ) ?  1000 :  $this -> MinSpaceWidth ;

				$repeat_count	=  abs ( round ( $char_offset / $space_width, 0 ) ) ;

				if  ( $repeat_count )
					$padding	=  str_repeat ( $this -> Separator, $repeat_count ) ;
				else
					$padding	=  $this -> Separator ;
				}
			else 
				$padding	=  $this -> Separator ;

			return ( utf8_encode ( $this -> Unescape ( $padding ) ) ) ;
		    }
		else
			return ( '' ) ;
	    }


	// __rtl_process -
	//	Processes the contents of a page when it contains characters belonging to an RTL language.
	private function  __rtl_process ( $text )
	   {
		$result		=  $text ;

		return ( $result ) ;
	    }


	// __strip_useless_instructions :
	//	Removes from a text stream all the Postscript instructions that are not meaningful for text extraction
	//	(these are mainly shape drawing instructions).
	private function  __strip_useless_instructions ( $data )
	   {
		$result		=  preg_replace ( self::$IgnoredInstructions, '', $data ) ;

		return ( $result ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        PeekAuthorInformation - Gets author information from the specified object data.
	
	    PROTOTYPE
	        $this -> PeekAuthorInformation ( $object_id, $object_data ) ;
	
	    DESCRIPTION
	        Try to check if the specified object data contains author information (ie, the /Author, /Creator, 
		/Producer, /ModDate, /CreationDate keywords) and sets the corresponding properties accordingly.
	
	    PARAMETERS
	    	$object_id (integer) -
	    		Object id of this text block.

	    	$object_data (string) -
	    		Stream contents.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function  PeekAuthorInformation ( $object_id, $object_data )
	   {
		static		$re	=  '#
						/
						(?P<keyword> [^ (]+)
						\(
						(?P<value> [^)]+)
					    #imsx' ;

		// To execute faster, run the regular expression only if the object data contains a /Author keyword
		if  ( ( strpos  ( $object_data, '/Author' )  !==  false  ||  strpos ( $object_data, '/CreationDate' )  !==  false )  &&  
				preg_match_all ( $re, $object_data, $matches ) )
		   {
			for  ( $i = 0, $count = count ( $matches [ 'keyword' ] ) ; $i  <  $count ; $i ++ )
			   {
				$keyword	=  $matches [ 'keyword' ] [$i] ;
				$value		=  $this -> __extract_chars_from_block ( $matches [ 'value' ] [$i] ) ;

				switch ( strtolower ( $keyword ) ) 
				   {
					case  'author'		:  $this -> Author			=  $value ; break ;
					case  'creator'		:  $this -> CreatorApplication		=  $value ; break ;
					case  'producer'	:  $this -> ProducerApplication		=  $value ; break ;
					case  'title'		:  $this -> Title			=  $value ; break ;
					case  'creationdate'	:  $this -> CreationDate		=  $this -> GetUTCDate ( $value ) ; break ;
					case  'moddate'		:  $this -> ModificationDate		=  $this -> GetUTCDate ( $value ) ; break ;
				    }
			    }

			$this -> GotAuthorInformation	=  true ;

			if  ( self::$DEBUG )
			   {
		   		echo "\n----------------------------------- AUTHOR INFORMATION\n" ;
				dump ( $this -> Author ) ;
				dump ( $this -> CreatorApplication ) ;
				dump ( $this -> ProducerApplication ) ;
				dump ( $this -> Title ) ;
				dump ( $this -> CreationDate ) ;
				dump ( $this -> ModificationDate ) ;
			    }
		    }
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    NAME
	        Unescape - Processes escape sequences from the specified string.

	    PROTOTYPE
	        $value	=  $this -> Unescape ( $text ) ;

	    DESCRIPTION
	        Processes escape sequences within the specified text. The recognized escape sequences are like the
		C-language ones : \b (backspace), \f (form feed), \r (carriage return), \n (newline), \t (tab).
		All other characters prefixed by "\" are returned as is.

	    PARAMETERS
	        $text (string) -
	                Text to be unescaped.

	    RETURN VALUE
	        Returns the unescaped value of $text.

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function   Unescape ( $text )
	   {
		$length 	=  strlen ( $text ) ;
		$result 	=  '' ;
		$ord0		=  ord ( 0 ) ;

		for  ( $i = 0 ; $i  <  $length ; $i ++ )
		   {
		   	$ch 	=  $text [$i] ;

			if  ( $ch  ==  '\\'  &&  isset ( $text [$i+1] ) )
			   {
				$nch 	=  $text [++$i] ;

				switch  ( $nch )
				   {
				   	case 	'b' 	:  $result .=  "\b" ; break ;
				   	case 	't' 	:  $result .=  "\t" ; break ;
				   	case 	'f' 	:  $result .=  "\f" ; break ;
				   	case 	'r' 	:  $result .=  "\r" ; break ;
				   	case 	'n' 	:  $result .=  "\n" ; break ;
				   	default 	:  
						// Octal escape notation 
						if  ( $nch  >=  '0'  &&  $nch  <=  '7' )
						   {
							$ord	=  ord ( $nch ) - $ord0 ;
							$i ++ ;

							while  ( $i  <  $length  &&  $text [$i]  >=  '0'  &&  $text [$i]  <=  '7' )
							   {
								$ord	=  ( $ord * 8 ) + ord ( $text [$i] ) - $ord0 ;
								$i ++ ;
							    }

							$i -- ;		// Count one character less since $i will be incremented at the end of the for() loop

							$result .= chr ( $ord ) ;
						    }
						else
							$result .=  $nch ;
				    }
			    }
			else
				$result 	.=  $ch ;
		    }

		return ( $result ) ;
	    }
    }


/*==============================================================================================================

    PdfTexterFontTable class -
        The PdfTexterFontTable class is not supposed to be used outside the context of the PdfToText class.
	Its purposes are to hold a list of font definitions taken from a pdf document, along with their
	associated character mapping tables, if any.
	This is why no provision has been made to design this class a a general purpose class ; its utility
	exists only in the scope of the PdfToText class.

  ==============================================================================================================*/
class 	PdfTexterFontTable 	extends PdfObjectBase
   {
	// Font table
	private		$Fonts		=  [] ;
	private		$DefaultFont	=  false ;
	// Font mapping between a font number and an object number
	private 	$FontMap 	=  [] ;
	// A character map buffer is used to store results from previous calls to the MapCharacter() method of the 
	// FontTable object. It dramatically reduces the number of calls needed, from one call for each character
	// defined in the pdf stream, to one call on each DISTINCT character defined in the PDF stream.
	// As an example, imagine a PDF file that contains 200K characters, but only 150 distinct ones. The
	// MapCharacter method will be called 150 times, instead of 200 000...
	private		$CharacterMapBuffer		=  [] ;


	// Constructor -
	//	Well, does not do anything special
	public function  __construct ( )
	   {
		parent::__construct ( ) ;
	    }


	// Add -
	//	Adds the current font declaration to the font table. Handles special cases where font id is not
	//	given by the object id, but rather by <</Rx...>> constructs
	public function  Add ( $object_id, $font_definition )
	   {
		if  ( PdfToText::$DEBUG )
		   {
	   		echo "\n----------------------------------- FONT #$object_id\n" ;
			echo $font_definition ;
		    }

		$font_type	=  PdfTexterFont::FONT_ENCODING_STANDARD ;
		$cmap_id	=  0 ;

		// Font resource id specification
	   	if  ( preg_match ( '#<< \s* (?P<rscdefs> /R\d+ .*) >>#ix', $font_definition, $match ) )
		   {
			$resource_definitions	=  $match [ 'rscdefs' ] ;

			preg_match_all ( '#/R (?P<font_id> \d+) #ix', $resource_definitions, $id_matches ) ;
			preg_match_all ( '#/ToUnicode \s* (?P<cmap_id> \d+)#ix', $resource_definitions, $cmap_matches ) ;

			$count		=  count ( $id_matches [ 'font_id' ] ) ;

			for  ( $i = 0 ;  $i  <  $count ; $i ++ )
			   {
				$font_id	=  $id_matches   [ 'font_id' ] [$i] ;
				$cmap_id	=  $cmap_matches [ 'cmap_id' ] [$i] ;

				$this -> Fonts [ $font_id ]	=  new  PdfTexterFont ( $font_id, $cmap_id, PdfTexterFont::FONT_ENCODING_UNICODE ) ;
			    }

			return ;
		    }
		// Font has an associated Unicode map (using the /ToUnicode keyword)
		else if  ( preg_match ( '#/ToUnicode \s* (?P<cmap> \d+)#ix', $font_definition, $match ) )
		   {
			$cmap_id	=  $match [ 'cmap' ] ;
			$font_type	=  PdfTexterFont::FONT_ENCODING_UNICODE_MAP ;
		    } 
		// Font has an associated character map (using a cmap id)
		else if  ( preg_match ( '#/Encoding \s* (?P<cmap> \d+) \s+ \d+ #ix', $font_definition, $match ) )
		   {
			$cmap_id 	=  $match [ 'cmap' ] ;
			$font_type	=  PdfTexterFont::FONT_ENCODING_PDF_MAP ;
		    }
		// Font uses the Windows Ansi encoding
		else if  ( preg_match ( '#/Encoding \s* /WinAnsiEncoding#ix', $font_definition ) )
			$font_type	=  PdfTexterFont::FONT_ENCODING_WINANSI ;
		// Font uses the Mac Roman encoding
		else if  ( preg_match ( '#/Encoding \s* /MacRomanEncoding#ix', $font_definition ) )
			$font_type	=  PdfTexterFont::FONT_ENCODING_MAC_ROMAN ;
	
		$this -> Fonts [ $object_id ]	=  new  PdfTexterFont ( $object_id, $cmap_id, $font_type ) ;

		// Arbitrarily set the default font to the first font encountered in the pdf file
		if  ( $this -> DefaultFont  ===  false )
		   {
			reset ( $this -> Fonts ) ;
			$this -> DefaultFont	=  key ( $this -> Fonts ) ;
		    }
	    }


	// AddFontMap -
	//	Process things like :
	//		<</F1 26 0 R/F2 22 0 R/F3 18 0 R>>
	//	which maps font 1 (when specified with the /Fx instruction) to object 26,
	//	2 to object 22 and 3 to object 18, respectively, in the above example.
	//	Found also a strange way of specifying a font mapping :
	//		<</f-0-0 5 0 R etc.
	//	And yet another one :
	//		<</C0_0 5 0 R
	public function  AddFontMap ( $object_id, $object_data )
	   {
		// The same object can hold different notations for font associations
		if  ( preg_match_all ( '# (?P<font> / ( (F) || (TT) || (R) ) \d+) \s+ (?P<object> \d+)#x', $object_data, $matches ) )
		   {
		   	for ( $i = 0, $count = count ( $matches [ 'font' ] ) ; $i  <  $count ; $i ++ )
		   		$this -> FontMap [ $matches [ 'font' ] [$i] ] 	=  $matches [ 'object' ] [$i] ;
		    }

		if  ( preg_match_all ( '# (?P<font> /f- \d+ - \d+ ) \s+ (?P<object> \d+)#x', $object_data, $matches ) )
		   {
		   	for ( $i = 0, $count = count ( $matches [ 'font' ] ) ; $i  <  $count ; $i ++ )
		   		$this -> FontMap [ $matches [ 'font' ] [$i] ] 	=  $matches [ 'object' ] [$i] ;
		    }

		if  ( preg_match_all ( '# (?P<font> /[CT] \d+ _ \d+) \s+ (?P<object> \d+)#x', $object_data, $matches ) )
		   {
		   	for ( $i = 0, $count = count ( $matches [ 'font' ] ) ; $i  <  $count ; $i ++ )
		   		$this -> FontMap [ $matches [ 'font' ] [$i] ] 	=  $matches [ 'object' ] [$i] ;
		    }
	    }


	// AddCharacterMap -
	//	Associates a character map to a font declaration that referenced it.
	public function  AddCharacterMap ( $cmap )
	   {
		foreach  ( $this -> Fonts  as  $font )
		   {
			if  ( $font -> CharacterMapId  ==  $cmap -> ObjectId )
			   {
				$font -> CharacterMap	=  $cmap ;

				return ( true ) ;
			    }
		    }

		return ( false ) ;
	    }


	// GetFontByMapId -
	//	Returns the font id (object id) associated to the specified mapped id.
	public function  GetFontByMapId ( $id )
	   {
		if  ( isset ( $this -> FontMap [ $id ] ) )
			return ( $this -> FontMap [ $id ] ) ;
		else
			return ( -1 ) ;
	    }

	// IsMapped -
	//	Checks if the specified font has an associated character map.
	public function  IsMapped ( $font )
	   {
		// For text contents that did not specify a font using the /Rx instruction,
		// use the first declared font as the default font
		if  ( $font  ==  -1 )
		   {
			reset ( $this -> Fonts ) ;
			$font 	=  key ( $this -> Fonts ) ;
		    }

		return ( isset ( $this -> Fonts [ $font ] )  &&
				$this -> Fonts [ $font ] -> CharacterMap ) ;
	    }


	// GetMapWidth -
	//	Returns the number of hex digits needed to represent a character in the specified font.
	public function  GetMapWidth ( $font )
	   {
		// For text contents that did not specify a font using the /Rx instruction,
		// use the first declared font as the default font
		if  ( $font  ==  -1 )
			$font	=  $this -> DefaultFont ;

		if  ( isset ( $this -> Fonts [ $font ] )  &&  $this -> Fonts [ $font ] -> CharacterMap )
		   {
			$width 	=  $this -> Fonts [ $font ] -> CharacterMap -> HexCharWidth ;

			if  ( $width )
				return ( $width ) ;
		    }

		return ( 2 ) ;
	    }


	// MapCharacter -
	//	Returns the character associated to the specified one.
	public function  MapCharacter ( $font, $ch, $return_false_on_failure = false )
	   {
		if  ( isset ( $this -> CharacterMapBuffer [ $font ] [ $ch ] ) )
			return ( $this -> CharacterMapBuffer [ $font ] [ $ch ] ) ;

		// Use the first declared font as the default font, if none defined
		if  ( $font  ==  -1 )
			$font	=  $this -> DefaultFont ;

		if  ( isset  ( $this -> Fonts [ $font ] ) )
			$code	=  $this -> Fonts [ $font ] -> MapCharacter ( $ch, $return_false_on_failure ) ;
		else
			$code	=  $this -> CodePointToUtf8 ( $ch ) ;

		$this -> CharacterMapBuffer [ $font ] [ $ch ]	=  $code ;

		return ( $code ) ;
	    }
    }



/*==============================================================================================================

    PdfTexterFont class -
        The PdfTexterFont class is not supposed to be used outside the context of the PdfToText class.
	It holds an optional character mapping table associted with this font.
	No provision has been made to design this class a a general purpose class ; its utility exists only in
	the scope of the PdfToText class.

  ==============================================================================================================*/
class  PdfTexterFont		extends PdfObjectBase
   {
	// Font encoding types, for fonts that are neither associated with a Unicode character map nor a PDF character map
	const	FONT_ENCODING_STANDARD		=  0 ;			// No character map, use the standard character set
	const	FONT_ENCODING_WINANSI		=  1 ;			// No character map, use the Windows Ansi character set
	const	FONT_ENCODING_MAC_ROMAN		=  2 ;			// No character map, use the MAC OS Roman character set
	const	FONT_ENCODING_UNICODE_MAP	=  3 ;			// Font has an associated unicode character map
	const	FONT_ENCODING_PDF_MAP		=  4 ;			// Font has an associated PDF character map

	// Windows Ansi mapping to Unicode. Only substitutions that have no direct equivalent are listed here
	// Source : https://msdn.microsoft.com/en-us/goglobal/cc305145.aspx
	// Only characters from 0x80 to 0x9F has no direct translation
	public static	$WinAnsiCharacterMap	=
	   [
		0x80	=>  0x20AC,
		0x82	=>  0x201A,
		0x83	=>  0x0192,
		0x84	=>  0x201E,
		0x85	=>  0x2026,
		0x86	=>  0x2020,
		0x87	=>  0x2021,
		0x88	=>  0x02C6,
		0x89	=>  0x2030,
		0x8A	=>  0x0160,
		0x8B	=>  0x2039,
		0x8C	=>  0x0152,
		0x8E	=>  0x017D,
		0x91	=>  0x2018,
		0x92	=>  0x2019,
		0x93	=>  0x201C,
		0x94	=>  0x201D,
		0x95	=>  0x2022,
		0x96	=>  0x2013,
		0x97	=>  0x2014,
		0x98	=>  0x02DC,
		0x99	=>  0x2122,
		0x9A	=>  0x0161,
		0x9B	=>  0x203A,
		0x9C	=>  0x0153,
		0x9E	=>  0x017E,
		0x9F	=>  0x0178
	    ] ;
	// Mac roman to Unicode encoding
	// Source : ftp://ftp.unicode.org/Public/MAPPINGS/VENDORS/APPLE/ROMAN.TXT
	public static	$MacRomanCharacterMap	=
	   [
		0x80	=>  0x00C4,	# LATIN CAPITAL LETTER A WITH DIAERESIS
		0x81	=>  0x00C5,	# LATIN CAPITAL LETTER A WITH RING ABOVE
		0x82	=>  0x00C7,	# LATIN CAPITAL LETTER C WITH CEDILLA
		0x83	=>  0x00C9,	# LATIN CAPITAL LETTER E WITH ACUTE
		0x84	=>  0x00D1,	# LATIN CAPITAL LETTER N WITH TILDE
		0x85	=>  0x00D6,	# LATIN CAPITAL LETTER O WITH DIAERESIS
		0x86	=>  0x00DC,	# LATIN CAPITAL LETTER U WITH DIAERESIS
		0x87	=>  0x00E1,	# LATIN SMALL LETTER A WITH ACUTE
		0x88	=>  0x00E0,	# LATIN SMALL LETTER A WITH GRAVE
		0x89	=>  0x00E2,	# LATIN SMALL LETTER A WITH CIRCUMFLEX
		0x8A	=>  0x00E4,	# LATIN SMALL LETTER A WITH DIAERESIS
		0x8B	=>  0x00E3,	# LATIN SMALL LETTER A WITH TILDE
		0x8C	=>  0x00E5,	# LATIN SMALL LETTER A WITH RING ABOVE
		0x8D	=>  0x00E7,	# LATIN SMALL LETTER C WITH CEDILLA
		0x8E	=>  0x00E9,	# LATIN SMALL LETTER E WITH ACUTE
		0x8F	=>  0x00E8,	# LATIN SMALL LETTER E WITH GRAVE
		0x90	=>  0x00EA,	# LATIN SMALL LETTER E WITH CIRCUMFLEX
		0x91	=>  0x00EB,	# LATIN SMALL LETTER E WITH DIAERESIS
		0x92	=>  0x00ED,	# LATIN SMALL LETTER I WITH ACUTE
		0x93	=>  0x00EC,	# LATIN SMALL LETTER I WITH GRAVE
		0x94	=>  0x00EE,	# LATIN SMALL LETTER I WITH CIRCUMFLEX
		0x95	=>  0x00EF,	# LATIN SMALL LETTER I WITH DIAERESIS
		0x96	=>  0x00F1,	# LATIN SMALL LETTER N WITH TILDE
		0x97	=>  0x00F3,	# LATIN SMALL LETTER O WITH ACUTE
		0x98	=>  0x00F2,	# LATIN SMALL LETTER O WITH GRAVE
		0x99	=>  0x00F4,	# LATIN SMALL LETTER O WITH CIRCUMFLEX
		0x9A	=>  0x00F6,	# LATIN SMALL LETTER O WITH DIAERESIS
		0x9B	=>  0x00F5,	# LATIN SMALL LETTER O WITH TILDE
		0x9C	=>  0x00FA,	# LATIN SMALL LETTER U WITH ACUTE
		0x9D	=>  0x00F9,	# LATIN SMALL LETTER U WITH GRAVE
		0x9E	=>  0x00FB,	# LATIN SMALL LETTER U WITH CIRCUMFLEX
		0x9F	=>  0x00FC,	# LATIN SMALL LETTER U WITH DIAERESIS
		0xA0	=>  0x2020,	# DAGGER
		0xA1	=>  0x00B0,	# DEGREE SIGN
		0xA2	=>  0x00A2,	# CENT SIGN
		0xA3	=>  0x00A3,	# POUND SIGN
		0xA4	=>  0x00A7,	# SECTION SIGN
		0xA5	=>  0x2022,	# BULLET
		0xA6	=>  0x00B6,	# PILCROW SIGN
		0xA7	=>  0x00DF,	# LATIN SMALL LETTER SHARP S
		0xA8	=>  0x00AE,	# REGISTERED SIGN
		0xA9	=>  0x00A9,	# COPYRIGHT SIGN
		0xAA	=>  0x2122,	# TRADE MARK SIGN
		0xAB	=>  0x00B4,	# ACUTE ACCENT
		0xAC	=>  0x00A8,	# DIAERESIS
		0xAD	=>  0x2260,	# NOT EQUAL TO
		0xAE	=>  0x00C6,	# LATIN CAPITAL LETTER AE
		0xAF	=>  0x00D8,	# LATIN CAPITAL LETTER O WITH STROKE
		0xB0	=>  0x221E,	# INFINITY
		0xB1	=>  0x00B1,	# PLUS-MINUS SIGN
		0xB2	=>  0x2264,	# LESS-THAN OR EQUAL TO
		0xB3	=>  0x2265,	# GREATER-THAN OR EQUAL TO
		0xB4	=>  0x00A5,	# YEN SIGN
		0xB5	=>  0x00B5,	# MICRO SIGN
		0xB6	=>  0x2202,	# PARTIAL DIFFERENTIAL
		0xB7	=>  0x2211,	# N-ARY SUMMATION
		0xB8	=>  0x220F,	# N-ARY PRODUCT
		0xB9	=>  0x03C0,	# GREEK SMALL LETTER PI
		0xBA	=>  0x222B,	# INTEGRAL
		0xBB	=>  0x00AA,	# FEMININE ORDINAL INDICATOR
		0xBC	=>  0x00BA,	# MASCULINE ORDINAL INDICATOR
		0xBD	=>  0x03A9,	# GREEK CAPITAL LETTER OMEGA
		0xBE	=>  0x00E6,	# LATIN SMALL LETTER AE
		0xBF	=>  0x00F8,	# LATIN SMALL LETTER O WITH STROKE
		0xC0	=>  0x00BF,	# INVERTED QUESTION MARK
		0xC1	=>  0x00A1,	# INVERTED EXCLAMATION MARK
		0xC2	=>  0x00AC,	# NOT SIGN
		0xC3	=>  0x221A,	# SQUARE ROOT
		0xC4	=>  0x0192,	# LATIN SMALL LETTER F WITH HOOK
		0xC5	=>  0x2248,	# ALMOST EQUAL TO
		0xC6	=>  0x2206,	# INCREMENT
		0xC7	=>  0x00AB,	# LEFT-POINTING DOUBLE ANGLE QUOTATION MARK
		0xC8	=>  0x00BB,	# RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK
		0xC9	=>  0x2026,	# HORIZONTAL ELLIPSIS
		0xCA	=>  0x00A0,	# NO-BREAK SPACE
		0xCB	=>  0x00C0,	# LATIN CAPITAL LETTER A WITH GRAVE
		0xCC	=>  0x00C3,	# LATIN CAPITAL LETTER A WITH TILDE
		0xCD	=>  0x00D5,	# LATIN CAPITAL LETTER O WITH TILDE
		0xCE	=>  0x0152,	# LATIN CAPITAL LIGATURE OE
		0xCF	=>  0x0153,	# LATIN SMALL LIGATURE OE
		0xD0	=>  0x2013,	# EN DASH
		0xD1	=>  0x2014,	# EM DASH
		0xD2	=>  0x201C,	# LEFT DOUBLE QUOTATION MARK
		0xD3	=>  0x201D,	# RIGHT DOUBLE QUOTATION MARK
		0xD4	=>  0x2018,	# LEFT SINGLE QUOTATION MARK
		0xD5	=>  0x2019,	# RIGHT SINGLE QUOTATION MARK
		0xD6	=>  0x00F7,	# DIVISION SIGN
		0xD7	=>  0x25CA,	# LOZENGE
		0xD8	=>  0x00FF,	# LATIN SMALL LETTER Y WITH DIAERESIS
		0xD9	=>  0x0178,	# LATIN CAPITAL LETTER Y WITH DIAERESIS
		0xDA	=>  0x2044,	# FRACTION SLASH
		0xDB	=>  0x20AC,	# EURO SIGN
		0xDC	=>  0x2039,	# SINGLE LEFT-POINTING ANGLE QUOTATION MARK
		0xDD	=>  0x203A,	# SINGLE RIGHT-POINTING ANGLE QUOTATION MARK
		0xDE	=>  0xFB01,	# LATIN SMALL LIGATURE FI
		0xDF	=>  0xFB02,	# LATIN SMALL LIGATURE FL
		0xE0	=>  0x2021,	# DOUBLE DAGGER
		0xE1	=>  0x00B7,	# MIDDLE DOT
		0xE2	=>  0x201A,	# SINGLE LOW-9 QUOTATION MARK
		0xE3	=>  0x201E,	# DOUBLE LOW-9 QUOTATION MARK
		0xE4	=>  0x2030,	# PER MILLE SIGN
		0xE5	=>  0x00C2,	# LATIN CAPITAL LETTER A WITH CIRCUMFLEX
		0xE6	=>  0x00CA,	# LATIN CAPITAL LETTER E WITH CIRCUMFLEX
		0xE7	=>  0x00C1,	# LATIN CAPITAL LETTER A WITH ACUTE
		0xE8	=>  0x00CB,	# LATIN CAPITAL LETTER E WITH DIAERESIS
		0xE9	=>  0x00C8,	# LATIN CAPITAL LETTER E WITH GRAVE
		0xEA	=>  0x00CD,	# LATIN CAPITAL LETTER I WITH ACUTE
		0xEB	=>  0x00CE,	# LATIN CAPITAL LETTER I WITH CIRCUMFLEX
		0xEC	=>  0x00CF,	# LATIN CAPITAL LETTER I WITH DIAERESIS
		0xED	=>  0x00CC,	# LATIN CAPITAL LETTER I WITH GRAVE
		0xEE	=>  0x00D3,	# LATIN CAPITAL LETTER O WITH ACUTE
		0xEF	=>  0x00D4,	# LATIN CAPITAL LETTER O WITH CIRCUMFLEX
		0xF0	=>  0xF8FF,	# Apple logo
		0xF1	=>  0x00D2,	# LATIN CAPITAL LETTER O WITH GRAVE
		0xF2	=>  0x00DA,	# LATIN CAPITAL LETTER U WITH ACUTE
		0xF3	=>  0x00DB,	# LATIN CAPITAL LETTER U WITH CIRCUMFLEX
		0xF4	=>  0x00D9,	# LATIN CAPITAL LETTER U WITH GRAVE
		0xF5	=>  0x0131,	# LATIN SMALL LETTER DOTLESS I
		0xF6	=>  0x02C6,	# MODIFIER LETTER CIRCUMFLEX ACCENT
		0xF7	=>  0x02DC,	# SMALL TILDE
		0xF8	=>  0x00AF,	# MACRON
		0xF9	=>  0x02D8,	# BREVE
		0xFA	=>  0x02D9,	# DOT ABOVE
		0xFB	=>  0x02DA,	# RING ABOVE
		0xFC	=>  0x00B8,	# CEDILLA
		0xFD	=>  0x02DD,	# DOUBLE ACUTE ACCENT
		0xFE	=>  0x02DB,	# OGONEK
		0xFF	=>  0x02C7	# CARON
	    ] ;
	// Font resource id (may be an object id, overridden by <</Rx...>> constructs
	public		$Id ;
	// Font type
	public		$FontType ;
	// Character map id, specified by the /ToUnicode flag
	public		$CharacterMapId ;
	// Optional character map, that may be set by the PdfToText::Load method just before processing text drawinf blocks
	public		$CharacterMap		=  null ;


	// Constructor -
	//	Builds a PdfTexterFont object, using its resource id and optional character map id.
	public function  __construct ( $resource_id, $cmap_id, $font_type )
	   {
		parent::__construct ( ) ;
		$this -> Id		=  $resource_id ;
		$this -> CharacterMapId	=  $cmap_id ;
		$this -> FontType	=  $font_type ;
	    }


	// MapCharacter -
	//	Returns the substitution string value for the specified character, if the current font has an
	//	associated character map, or the original character encoded in utf8, if not.
	public function  MapCharacter ( $ch, $return_false_on_failure = false )
	   {
		if  ( $this -> CharacterMap )
		   {
			if  ( isset ( $this -> CharacterMap [ $ch ] ) )
				return ( $this -> CharacterMap [ $ch ] ) ;
		    }
		else if  ( $this -> FontType  ==  self::FONT_ENCODING_WINANSI )
		   {
			if  ( isset ( self::$WinAnsiCharacterMap [ $ch ] ) )
				return ( $this -> CodePointToUtf8 ( self::$WinAnsiCharacterMap [ $ch ] ) ) ;
		    }  
		else if  ( $this -> FontType  ==  self::FONT_ENCODING_MAC_ROMAN )
		   {
			if  ( isset ( self::$MacRomanCharacterMap [ $ch ] ) )
				return ( $this -> CodePointToUtf8 ( self::$MacRomanCharacterMap [ $ch ] ) ) ;
		    }  

		if  ( $return_false_on_failure )
			return ( false ) ;

		return ( $this -> CodePointToUtf8 ( $ch ) ) ;
	    }
    }


/*==============================================================================================================

    PdfTexterCharacterMap -
        The PdfTexterFont class is not supposed to be used outside the context of the PdfToText class.
	Describes a character map.
	No provision has been made to design this class a a general purpose class ; its utility exists only in
	the scope of the PdfToText class.

  ==============================================================================================================*/
abstract class	PdfTexterCharacterMap	extends		PdfObjectBase
					implements	\ArrayAccess, \Countable
   {
	// Object id of the character map
	public		$ObjectId ;
	// Number of hex digits in a character represented in hexadecimal notation
	public 		$HexCharWidth ;



	public function  __construct ( $object_id )
	   {
		parent::__construct ( ) ;
		$this -> ObjectId	=  $object_id ;
	    }


	/*--------------------------------------------------------------------------------------------------------------

	    CreateInstance -
	        Creates a PdfTexterCharacterMap instance of the correct type.

	 *-------------------------------------------------------------------------------------------------------------*/
	public static function  CreateInstance ( $object_id, $definitions )
	   {
		if  ( stripos ( $definitions, 'begincmap'    )  !==  false  ||  
		      stripos ( $definitions, 'beginbfchar'  )  !==  false  ||
		      stripos ( $definitions, 'beginbfrange' )  !==  false )
			return ( new PdfTexterUnicodeMap ( $object_id, $definitions ) ) ;
		else if  ( stripos ( $definitions, '/Differences' )  !==  false )
			return ( new PdfTexterEncodingMap ( $object_id, $definitions ) ) ;
		else
			return ( false ) ;
	    }



	/*--------------------------------------------------------------------------------------------------------------

	        Interface implementations.

	 *-------------------------------------------------------------------------------------------------------------*/
	abstract function  count 		( ) ;
	abstract function  offsetExists 	( $offset ) ;
	abstract function  offsetGet 		( $offset ) ;

	public function  offsetSet ( $offset, $value )
	   { error ( new PdfToTextException ( "Unsupported operation." ) ) ; }

	public function  offsetUnset ( $offset )
	   { error ( new PdfToTextException ( "Unsupported operation." ) ) ; }
    }



/*==============================================================================================================

    PdfTexterUnicodeMap -
        A class for fonts having a character map specified with the /ToUnicode parameter.

  ==============================================================================================================*/
class  PdfTexterUnicodeMap 	extends 	PdfTexterCharacterMap
    {
	// Id of the character map (specified by the /Rx flag)
	public		$Id	;
	// Character substitution table, using the beginbfrange/endbfrange notation
	// Only constructs of the form :
	//	<low> <high> <start>
	// are stored in this table. Constructs of the form :
	//	<x> <y> [ <subst_x> <subst_x+1> ... <subst_y> ]
	// are stored in the $DirectMap array, because it is conceptually the same thing in the end as a character substitution being
	// defined with the beginbfchar/endbfchar construct.
	// Note that a dichotomic search in $RangeMap will be performed for each character reference not yet seen in the pdf flow.
	// Once the substitution character has been found, it will be added to the $DirectMap array for later faster access.
	// The reason for this optimization is that some pdf files can contain beginbfrange/endbfrange constructs that may seem useless,
	// except for validation purposes (ie, validating the fact that a character reference really belongs to the character map).
	// However, such constructs can lead to thousands of character substitutions ; consider the following example, that comes
	// from a sample I received :
	//	beginbfrange
	//	<1000> <1FFFF> <1000>
	//	<2000> <2FFFF> <2000>
	//	...
	//	<A000> <AFFFF> <A0000>
	//	...
	//	endbfrange
	// By naively storing a one-to-one character relationship in an associative array, such as :
	//	$array [ 0x1000 ] = 0x1000 ;
	//	$array [ 0x1001 ] = 0x1001 ;
	//	..
	//	$array [ 0x1FFF ] = 0x1FFF ;
	//	etc.
	// you may arrive to a situation where the array becomes so big that it exhausts all of the available memory.
	// This is why the ranges are stored as is and a dichotomic search is performed to go faster.
	// Since it is useless to use this method to search the same character twice, when it has been found once, the
	// substitution pair will be put in the $DirectMap array for subsequent accesses (there is little probability that a PDF
	// file contains so much different characters, unless you are processing the whole Unicode table itself ! - but in this
	// case, you will simply have to adjust the value of the memory_limit setting in your php.ini file. Consider that I am
	// not a magician...).
	protected	$RangeMap		=  [] ;
	private		$RangeCount		=  0 ;				// Avoid unnecessary calls to the count() function
	private		$RangeMin		=  PHP_INT_MAX,			// Min and max values of the character ranges
			$RangeMax		=  -1 ;
	// Character substitution table for tables using the beginbfchar notation
	protected	$DirectMap		=  [] ;


	// Constructor -
	//	Analyzes the text contents of a CMAP and extracts mappings from the beginbfchar/endbfchar and
	//	beginbfrange/endbfrange constructs.
	public function  __construct ( $object_id, $definitions )
	   {
		parent::__construct ( $object_id ) ;

		if  ( PdfToText::$DEBUG )
		   {
	   		echo "\n----------------------------------- UNICODE CMAP #$object_id\n" ;
			echo $definitions;
		    }

		// Retrieve the cmap id, if any
		preg_match ( '# /CMapName \s* /R (?P<num> \d+) #ix', $definitions, $match ) ;
		$this -> Id 		=  isset ( $match [ 'num' ] ) ?  $match [ 'num' ] : -1 ;

		// Get the codespace range, which will give us the width of a character specified in hexadecimal notation
		preg_match ( '# begincodespacerange \s+ <\s* (?P<low> [0-9a-f]+) \s*> \s* <\s* (?P<high> [0-9a-f]+) \s*> \s*endcodespacerange #ix', $definitions, $match ) ;

		$this -> HexCharWidth 	=  max ( strlen ( $match [ 'low' ] ), strlen ( $match [ 'high' ] ) ) ;

		// Process beginbfchar/endbfchar constructs
		if  ( preg_match_all ( '/ beginbfchar \s* (?P<chars> .*?) endbfchar /imsx', $definitions, $char_matches ) )
		    {
		    	foreach  ( $char_matches [ 'chars' ]  as  $char_list )
		    	   {
				// Don't believe that the contents between beginbfchar/endbfchar are separated by line breaks !
				// I have met one example where everything was put on the same line
				preg_match_all ( '/ < \s* (?P<char> [0-9a-f]+) \s* > /imx', $char_list, $code_matches ) ;

				for  ( $i = 0, $count = count ( $code_matches [ 'char' ] ) ; $i  <  $count ; $i += 2 )
				   {
					   $src		=  hexdec ( $code_matches [ 'char' ] [ $i ] ) ;
					   $dst		=  hexdec ( $code_matches [ 'char' ] [ $i + 1 ] ) ;

					   $this -> DirectMap [ $src ]	=  $dst ;
				    }
		    	    }
		     }

		// Process beginbfrange/endbfrange constructs
		if  ( preg_match_all ( '/ beginbfrange \s* (?P<ranges> .*?) endbfrange /imsx', $definitions, $range_matches ) )
		   {
			foreach  ( $range_matches [ 'ranges' ]  as  $range_list )
			   {
				// One day I think that I will find a pdf sample where the definitions are put on the same line...
				// Until that, consider everything is separated by a newline 
			   	$ranges 	=  explode ( "\n", trim ( $range_list ) ) ;

				// Loop through each range definition
				foreach  ( $ranges  as  $range )
				   {
					// Normal form :
					//	<from><to><start>
					//	Generates mappings from start+from to start+to.
					if  ( preg_match ( '/ <\s* (?P<from> [0-9a-f]+) \s*> \s* <\s* (?P<to> [0-9a-f]+) \s* > \s* <\s* (?P<subst> [0-9a-f]+) \s* > /imx',
									$range, $range_match ) )
					   {
						$from 		=  hexdec ( $range_match [ 'from'  ] ) ;
						$to 		=  hexdec ( $range_match [ 'to'    ] ) ;
						$subst 		=  hexdec ( $range_match [ 'subst' ] ) ;

						if  ( $from  !=  $to )
						   {
							$this -> RangeMap []	=  [ $from, $to, $subst ] ;

							// Adjust min and max values for the ranges stored in this character map - to avoid unnecessary testing
							if  ( $from  <  $this -> RangeMin )
								$this -> RangeMin	=  $from ;

							if  ( $to  >  $this -> RangeMax )
								$this -> RangeMax	=  $to ;
						    }
						else
							$this -> DirectMap [ $from ]	=  $subst ;
					    }
					// Array form :
					//	<from><to>[ value1, value2, ... valuen ]
					//	Generates mapping from "from" to "to" using the specified values. The value count
					//	between square brackets must be the same as to - from + 1.
					// We put them in the DirectMap array, since there is only a one to one correspondance
				   	else if  ( preg_match ( '/ < \s* (?P<from> [0-9a-f]+) \s* > \s* < \s* (?P<to> [0-9a-f]+) \s* > \s* \[(?P<subst> .*?)\] /imx',
									$range, $range_match ) )
					   {
						$from 		=  hexdec ( $range_match [ 'from'  ] ) ;
						$to 		=  hexdec ( $range_match [ 'to'    ] ) ;
						$subst 		=  preg_split ( '/\s+/',  trim ( $range_match [ 'subst' ] ) ) ;

						for  ( $i = $from, $count = 0 ; $i  <=  $to ; $i ++, $count ++ )
							$this -> DirectMap [$i] 	=  hexdec ( $subst [ $count ] ) ;
					    }
				   }
			    }

			// Sort the ranges by their starting offsets 
			$this -> RangeCount	=  count ( $this -> RangeMap ) ;

			if  ( $this -> RangeCount  >  1 )
			   {
				usort
				   (
					$this -> RangeMap,
					function  ( $a, $b )
					   { return ( $a [0] - $b [0] ) ; }
				    ) ;
			    }
		    }
	     }


	/*--------------------------------------------------------------------------------------------------------------

	        Interface implementations.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function  count ( )
	   { return ( count ( $this -> DirectMap ) ) ; }


	public function  offsetExists ( $offset )
	   { return  ( $this -> offsetGetSafe ( $offset )  !==  false ) ; }


	public function  offsetGetSafe ( $offset )
	   {
		// An easy optimization : since accessing character maps is done using code like this :
		//	if  ( isset ( ... -> CharacterMap [$index] ) )
		//		$ch	=  ... -> CharacterMap [$index] ;
		// and that both the offsetExists() and offsetGet() methods use offsetGetSafe(), we are sure that a call to
		// isset() is immediately followed by an array access, so remember the last value that was found during the
		// call to isset()
		static		$last_code		=  -1,
				$last_offset		=  -1 ;

		if  ( $offset  ==  $last_offset )
			return ( $last_code ) ;

		// Return value
		$code	=  false ;

		// Character already has an entry (character reference => subtituted character)
		if  ( isset ( $this -> DirectMap [ $offset ] ) )
			$code	=  $this -> CodePointToUtf8 ( $this -> DirectMap [ $offset ] ) ;
		// Character does not has a direct entry ; have a look in the character ranges defined for this map
		else if  ( $this -> RangeCount  &&  $offset  >=  $this -> RangeMin  &&  $offset  <=  $this -> RangeMax )
		   {
			$low		=  0 ;
			$high		=  count ( $this -> RangeMap ) - 1 ;
			$result		=  false ;

			// Use a dichotomic search through character ranges
			while  ( $low  <=  $high )
			   {
				$middle		=  ( $low + $high )  >>  1 ;

				if  ( $offset  <  $this -> RangeMap [ $middle ] [0] )
					$high	=  $middle - 1 ;
				else if  ( $offset  >  $this -> RangeMap [ $middle ] [1] )
					$low	=  $middle + 1 ;
				else
				   {
					$result	=  $this -> RangeMap [ $middle ] [2] + $offset - $this -> RangeMap [ $middle ] [0] ;
					break ;
				    }
			    }

			// Once a character has been found in the ranges defined by this character map, store it in the DirectMap property
			// so that it will be directly retrieved during subsequent accesses
			if  ( $result  !==  false )
			   {
				$code				=  $this -> CodePointToUtf8 ( $result ) ;
				$this -> DirectMap [ $offset ]	=  $result ;
			    }
		    }

		// Remember last queried offset and its corresponding code
		$last_code	=  $code ;
		$last_offset	=  $offset ;

		// All done, return
		return ( $code ) ;
	    }


	public function  offsetGet ( $offset )
	   {
		$code	=  $this -> offsetGetSafe ( $offset ) ;

		if  ( $code  === false )
			$code	=  $this -> CodePointToUtf8 ( $offset ) ;

		return ( $code ) ;
	    }
    }


/*==============================================================================================================

    PdfTexterEncodingMap -
        A class for fonts having a character map specified with the /Encoding parameter.

  ==============================================================================================================*/
class  PdfTexterEncodingMap 	extends  PdfTexterCharacterMap
   {
	// Possible encodings (there is a 5th one, MacExpertEncoding, but used for "expert fonts" ; no need to deal
	// with it here since we only want to extract text)
	// Note that the values of these constants are direct indices to the second dimension of the $Encodings table
	const 	PDF_STANDARD_ENCODING 		=  0 ;
	const 	PDF_MAC_ROMAN_ENCODING 		=  1 ;
	const 	PDF_WIN_ANSI_ENCODING 		=  2 ;
	const 	PDF_DOC_ENCODING 		=  3 ;

	// Correspondance between an encoding name and its corresponding character in the
	// following format : Standard, Mac, Windows, Pdf
	private static 		$Encodings 	=
	   [
		'A'			=>  [ 0101, 0101, 0101, 0101 ],
	   	'AE'			=>  [ 0341, 0256, 0306, 0306 ],
	   	'Aacute'		=>  [    0, 0347, 0301, 0301 ],
	   	'Acircumflex'		=>  [    0, 0345, 0302, 0302 ],
		'Adieresis'		=>  [    0, 0200, 0304, 0304 ],
		'Agrave'		=>  [    0, 0313, 0300, 0300 ],
		'Aring'			=>  [    0, 0201, 0305, 0305 ],
		'Atilde'		=>  [    0, 0314, 0303, 0303 ],
		'B'			=>  [ 0102, 0102, 0102, 0102 ],
	   	'C' 			=>  [ 0103, 0103, 0103, 0103 ],
		'Ccedilla'		=>  [    0, 0202, 0307, 0307 ],
		'D'			=>  [ 0104, 0104, 0104, 0104 ],
	   	'E' 			=>  [ 0105, 0105, 0105, 0105 ],
		'Eacute'		=>  [    0, 0203, 0311, 0311 ],
		'Ecircumflex'		=>  [    0, 0346, 0312, 0312 ],
		'Edieresis'		=>  [    0, 0350, 0313, 0313 ],
		'Egrave'		=>  [    0, 0351, 0310, 0310 ],
		'Eth' 			=>  [    0,    0, 0320, 0320 ],
		'Euro'			=>  [    0,    0, 0200, 0240 ],
		'F'			=>  [ 0106, 0106, 0106, 0106 ],
		'G'			=>  [ 0107, 0107, 0107, 0107 ],
		'H'			=>  [ 0110, 0110, 0110, 0110 ],
		'I'			=>  [ 0111, 0111, 0111, 0111 ],
	   	'Iacute'		=>  [    0, 0352, 0315, 0315 ],
	   	'Icircumflex'		=>  [    0, 0353, 0316, 0316 ],
		'Idieresis'		=>  [    0, 0354, 0317, 0317 ],
		'Igrave'		=>  [    0, 0355, 0314, 0314 ],
		'J'			=>  [ 0112, 0112, 0112, 0112 ],
		'K'			=>  [ 0113, 0113, 0113, 0113 ],
		'L'			=>  [ 0114, 0114, 0114, 0114 ],
		'Lslash'		=>  [ 0350,    0,    0, 0225 ],
		'M'			=>  [ 0115, 0115, 0115, 0115 ],
		'N'			=>  [ 0116, 0116, 0116, 0116 ],
		'Ntilde'		=>  [    0, 0204, 0321, 0321 ],
	   	'O'			=>  [ 0117, 0117, 0117, 0117 ],
		'OE' 			=>  [ 0352, 0316, 0214, 0226 ],
		'Oacute' 		=>  [    0, 0356, 0323, 0323 ],
		'Ocircumflex'		=>  [    0, 0357, 0324, 0324 ],
		'Odieresis'		=>  [    0, 0205, 0326, 0326 ],
		'Ograve'		=>  [    0, 0361, 0322, 0322 ],
		'Oslash' 		=>  [ 0351, 0257, 0330, 0330 ],
		'Otilde' 		=>  [    0, 0315, 0325, 0325 ],
	   	'P'			=>  [ 0120, 0120, 0120, 0120 ],
	   	'Q'			=>  [ 0121, 0121, 0121, 0121 ],
	   	'R'			=>  [ 0122, 0122, 0122, 0122 ],
	   	'S'			=>  [ 0123, 0123, 0123, 0123 ],
		'Scaron'		=>  [    0,    0, 0212, 0227 ],
		'T'			=>  [ 0124, 0124, 0124, 0124 ],
		'Thorn'			=>  [    0,    0, 0336, 0336 ],
		'U'			=>  [ 0125, 0125, 0125, 0125 ],
		'Uacute'		=>  [    0, 0362, 0332, 0332 ],
		'Ucircumflex'		=>  [    0, 0363, 0333, 0333 ],
		'Udieresis'		=>  [    0, 0206, 0334, 0334 ],
		'Ugrave'		=>  [    0, 0364, 0331, 0331 ],
		'V'			=>  [ 0126, 0126, 0126, 0126 ],
		'W'			=>  [ 0127, 0127, 0127, 0127 ],
		'X'			=>  [ 0130, 0130, 0130, 0130 ],
		'Y'			=>  [ 0131, 0131, 0131, 0131 ],
		'Yacute'		=>  [    0,    0, 0335, 0335 ],
		'Ydieresis'		=>  [    0, 0331, 0237, 0230 ],
		'Z'			=>  [ 0132, 0132, 0132, 0132 ],
	   	'Zcaron'		=>  [    0,    0, 0216, 0231 ],
		'a' 			=>  [ 0141, 0141, 0141, 0141 ],
		'aacute'		=>  [    0, 0207, 0341, 0341 ],
		'acircumflex'		=>  [    0, 0211, 0342, 0342 ],
		'acute'			=>  [ 0302, 0253, 0264, 0264 ],
		'adieresis'		=>  [    0, 0212, 0344, 0344 ],
		'ae'			=>  [ 0361, 0276, 0346, 0346 ],
		'agrave' 		=>  [    0, 0210, 0340, 0340 ],
		'ampersand' 		=>  [ 0046, 0046, 0046, 0046 ],
		'aring' 		=>  [    0, 0214, 0345, 0345 ],
		'asciicircum' 		=>  [ 0136, 0136, 0136, 0136 ],
		'asciitilde'		=>  [ 0176, 0176, 0176, 0176 ],
		'asterisk' 		=>  [ 0052, 0052, 0052, 0052 ],
		'at'			=>  [ 0100, 0100, 0100, 0100 ],
		'atilde'		=>  [    0, 0213, 0343, 0343 ],
		'b' 			=>  [ 0142, 0142, 0142, 0142 ],
		'backslash' 		=>  [ 0134, 0134, 0134, 0134 ],
		'bar' 			=>  [ 0174, 0174, 0174, 0174 ],
		'braceleft'		=>  [ 0173, 0173, 0173, 0173 ],
		'braceright' 		=>  [ 0175, 0175, 0175, 0175 ],
		'bracketleft' 		=>  [ 0133, 0133, 0133, 0133 ],
		'bracketright' 		=>  [ 0135, 0135, 0135, 0135 ],
		'breve'			=>  [ 0306, 0371,    0, 0030 ],
		'brokenbar' 		=>  [    0,    0, 0246, 0246 ],
		'bullet' 		=>  [ 0267, 0245, 0225, 0200 ],
		'c'			=>  [ 0143, 0143, 0143, 0143 ],
		'caron'			=>  [ 0317, 0377,    0, 0031 ],
		'ccedilla'		=>  [    0, 0215, 0347, 0347 ],
		'cedilla'		=>  [ 0313, 0374, 0270, 0270 ],
		'cent' 			=>  [ 0242, 0242, 0242, 0242 ],
		'circumflex' 		=>  [ 0303, 0366, 0210, 0032 ],
		'colon' 		=>  [ 0072, 0072, 0072, 0072 ],
		'comma'			=>  [ 0054, 0054, 0054, 0054 ],
		'copyright'		=>  [    0, 0251, 0251, 0251 ],
		'currency'		=>  [ 0250, 0333, 0244, 0244 ],
		'd'			=>  [ 0144, 0144, 0144, 0144 ],
		'dagger' 		=>  [ 0262, 0240, 0206, 0201 ],
		'daggerdbl' 		=>  [ 0263, 0340, 0207, 0202 ],
		'degree' 		=>  [    0, 0241, 0260, 0260 ],
		'dieresis'		=>  [ 0310, 0254, 0250, 0250 ],
		'divide' 		=>  [    0, 0326, 0367, 0367 ],
		'dollar' 		=>  [ 0044, 0044, 0044, 0044 ],
		'dotaccent' 		=>  [ 0307, 0372,    0, 0033 ],
		'dotlessi'		=>  [ 0365, 0365,    0, 0232 ],
		'e' 			=>  [ 0145, 0145, 0145, 0145 ],
		'eacute'		=>  [    0, 0216, 0351, 0351 ],
		'ecircumflex'		=>  [    0, 0220, 0352, 0352 ],
		'edieresis' 		=>  [    0, 0221, 0353, 0353 ],
		'egrave'		=>  [    0, 0217, 0350, 0350 ],
		'eight' 		=>  [ 0070, 0070, 0070, 0070 ],
		'ellipsis' 		=>  [ 0274, 0311, 0205, 0203 ],
		'emdash' 		=>  [ 0320, 0321, 0227, 0204 ],
		'endash' 		=>  [ 0261, 0320, 0226, 0205 ],
		'equal' 		=>  [ 0075, 0075, 0075, 0075 ],
		'eth'			=>  [    0,    0, 0360, 0360 ],
		'exclam' 		=>  [ 0041, 0041, 0041, 0041 ],
		'exclamdown' 		=>  [ 0241, 0301, 0241, 0241 ],
		'f' 			=>  [ 0146, 0146, 0146, 0146 ],
		'fi' 			=>  [ 0256, 0336,    0, 0223 ],
		'five' 			=>  [ 0065, 0065, 0065, 0065 ],
		'fl' 			=>  [ 0257, 0337,    0, 0224 ],
		'florin' 		=>  [ 0246, 0304, 0203, 0206 ],
		'four'			=>  [ 0064, 0064, 0064, 0064 ],
		'fraction'		=>  [ 0244, 0332,    0, 0207 ],
		'g' 			=>  [ 0147, 0147, 0147, 0147 ],
		'germandbls'		=>  [ 0373, 0247, 0337, 0337 ],
		'grave' 		=>  [ 0301, 0140, 0140, 0140 ],
		'greater' 		=>  [ 0076, 0076, 0076, 0076 ],
		'guillemotleft'		=>  [ 0253, 0307, 0253, 0253 ],
		'guillemotright' 	=>  [ 0273, 0310, 0273, 0273 ],
		'guilsinglleft'		=>  [ 0254, 0334, 0213, 0210 ],
		'guilsinglright'	=>  [ 0255, 0335, 0233, 0211 ],
		'h'			=>  [ 0150, 0150, 0150, 0150 ],
		'hungarumlaut'		=>  [ 0315, 0375,    0, 0034 ],
		'hyphen' 		=>  [ 0055, 0055, 0055, 0055 ],
		'i' 			=>  [ 0151, 0151, 0151, 0151 ],
		'iacute'		=>  [    0, 0222, 0355, 0355 ],
		'icircumflex' 		=>  [    0, 0224, 0356, 0356 ],
		'idieresis'		=>  [    0, 0225, 0357, 0357 ],
		'igrave' 		=>  [    0, 0223, 0354, 0354 ],
		'j' 			=>  [ 0152, 0152, 0152, 0152 ],
		'k' 			=>  [ 0153, 0153, 0153, 0153 ],
		'l' 			=>  [ 0154, 0154, 0154, 0154 ],
		'less'			=>  [ 0074, 0074, 0074, 0074 ],
		'logicalnot' 		=>  [    0, 0302, 0254, 0254 ],
		'lslash'		=>  [ 0370,    0,    0, 0233 ],
		'm'			=>  [ 0155, 0155, 0155, 0155 ],
		'macron'		=>  [ 0305, 0370, 0257, 0257 ],
		'minus' 		=>  [    0,    0,    0, 0212 ],
		'mu' 			=>  [    0, 0265, 0265, 0265 ],
		'multiply'		=>  [    0,    0, 0327, 0327 ],
		'n' 			=>  [ 0156, 0156, 0156, 0156 ],
		'nine' 			=>  [ 0071, 0071, 0071, 0071 ],
		'ntilde' 		=>  [    0, 0226, 0361, 0361 ],
		'numbersign' 		=>  [ 0043, 0043, 0043, 0043 ],
		'o'			=>  [ 0157, 0157, 0157, 0157 ],
		'oacute' 		=>  [    0, 0227, 0363, 0363 ],
		'ocircumflex' 		=>  [    0, 0231, 0364, 0364 ],
		'odieresis'		=>  [    0, 0232, 0366, 0366 ],
		'oe' 			=>  [ 0372, 0317, 0234, 0234 ],
		'ogonek' 		=>  [ 0316, 0376,    0, 0035 ],
		'ograve'		=>  [    0, 0230, 0362, 0362 ],
		'one' 			=>  [ 0061, 0061, 0061, 0061 ],
		'onehalf' 		=>  [    0,    0, 0275, 0275 ],
		'onequarter' 		=>  [    0,    0, 0274, 0274 ],
		'onesuperior'		=>  [    0,    0, 0271, 0271 ],
		'ordfeminine' 		=>  [ 0343, 0273, 0252, 0252 ],
		'ordmasculine' 		=>  [ 0353, 0274, 0272, 0272 ],
		'oslash'		=>  [ 0371, 0277, 0370, 0370 ],
		'otilde' 		=>  [    0, 0233, 0365, 0365 ],
		'p'			=>  [ 0160, 0160, 0160, 0160 ],
		'paragraph' 		=>  [ 0266, 0246, 0266, 0266 ],
		'parenleft' 		=>  [ 0050, 0050, 0050, 0050 ],
		'parenright'		=>  [ 0051, 0051, 0051, 0051 ],
		'percent' 		=>  [ 0045, 0045, 0045, 0045 ],
		'period' 		=>  [ 0056, 0056, 0056, 0056 ],
		'periodcentered'	=>  [ 0264, 0341, 0267, 0267 ],
		'perthousand' 		=>  [ 0275, 0344, 0211, 0213 ],
		'plus' 			=>  [ 0053, 0053, 0053, 0053 ],
		'plusminus' 		=>  [    0, 0261, 0261, 0261 ],
		'q' 			=>  [ 0161, 0161, 0161, 0161 ],
		'question' 		=>  [ 0077, 0077, 0077, 0077 ],
		'questiondown' 		=>  [ 0277, 0300, 0277, 0277 ],
		'quotedbl' 		=>  [ 0042, 0042, 0042, 0042 ],
		'quotedblbase' 		=>  [ 0271, 0343, 0204, 0214 ],
		'quotedblleft'		=>  [ 0252, 0322, 0223, 0215 ],
		'quotedblright'		=>  [ 0272, 0323, 0224, 0216 ],
		'quoteleft' 		=>  [ 0140, 0324, 0221, 0217 ],
		'quoteright'		=>  [ 0047, 0325, 0222, 0220 ],
		'quotesinglbase'	=>  [ 0270, 0342, 0202, 0221 ],
		'quotesingle'		=>  [ 0251, 0047, 0047, 0047 ],
		'r'			=>  [ 0162, 0162, 0162, 0162 ],
		'registered' 		=>  [    0, 0250, 0256, 0256 ],
		'ring' 			=>  [ 0312, 0373,    0, 0036 ],
		's'			=>  [ 0163, 0163, 0163, 0163 ],
		'scaron'		=>  [    0,    0, 0232, 0235 ],
		'section'		=>  [ 0247, 0244, 0247, 0247 ],
		'semicolon' 		=>  [ 0073, 0073, 0073, 0073 ],
		'seven' 		=>  [ 0067, 0067, 0067, 0067 ],
		'six' 			=>  [ 0066, 0066, 0066, 0066 ],
		'slash' 		=>  [ 0057, 0057, 0057, 0057 ],
		'space' 		=>  [ 0040, 0040, 0040, 0040 ],
		'sterling'		=>  [ 0243, 0243, 0243, 0243 ],
		't'			=>  [ 0164, 0164, 0164, 0164 ],
		'thorn' 		=>  [    0,    0, 0376, 0376 ],
		'three'			=>  [ 0063, 0063, 0063, 0063 ],
		'threequarters'		=>  [    0,    0, 0276, 0276 ],
		'threesuperior' 	=>  [    0,    0, 0263, 0263 ],
		'tilde'			=>  [ 0304, 0367, 0230, 0037 ],
		'trademark' 		=>  [    0, 0252, 0231, 0222 ],
		'two' 			=>  [ 0062, 0062, 0062, 0062 ],
		'twosuperior'		=>  [    0,    0, 0262, 0262 ],
		'u' 			=>  [ 0165, 0165, 0165, 0165 ],
		'uacute'		=>  [    0, 0234, 0372, 0372 ],
		'ucircumflex' 		=>  [    0, 0236, 0373, 0373 ],
		'udieresis'		=>  [    0, 0237, 0374, 0374 ],
		'ugrave' 		=>  [    0, 0235, 0371, 0371 ],
		'underscore' 		=>  [ 0137, 0137, 0137, 0137 ],
		'v' 			=>  [ 0166, 0166, 0166, 0166 ],
		'w' 			=>  [ 0167, 0167, 0167, 0167 ],
		'x' 			=>  [ 0170, 0170, 0170, 0170 ],
		'y' 			=>  [ 0171, 0171, 0171, 0171 ],
		'yacute' 		=>  [    0,    0, 0375, 0375 ],
		'ydieresis' 		=>  [    0, 0330, 0377, 0377 ],
		'yen' 			=>  [ 0245, 0264, 0245, 0245 ],
		'z'			=>  [ 0172, 0172, 0172, 0172 ],
		'zcaron' 		=>  [    0,    0, 0236, 0236 ],
		'zero' 			=>  [ 0060, 0060, 0060, 0060 ]
	   ] ;


	// Encoding type (one of the PDF_*_ENCODING constants)
	public 		$Encoding ;
	// Differences array (a character substitution table to the standard encodings)
	protected 	$Map 		=  [] ;


   	// Constructor -
	//	Analyzes the text contents of a CMAP and extracts mappings from the beginbfchar/endbfchar and
	//	beginbfrange/endbfrange constructs.
	public function  __construct ( $object_id, $definitions )
	   {
		parent::__construct ( $object_id ) ;

		$this -> HexCharWidth	=  2 ;

		if  ( PdfToText::$DEBUG )
		   {
	   		echo "\n----------------------------------- ENCODING CMAP #$object_id\n" ;
			echo $definitions;
		    }

		// Retrieve text encoding
		preg_match ( '# / (?P<encoding> (WinAnsiEncoding) | (PDFDocEncoding) | (MacRomanEncoding) | (StandardEncoding) ) #ix',
				$definitions, $encoding_match ) ;

		if ( ! isset ( $encoding_match [ 'encoding' ] ) )
			$encoding_match [ 'encoding' ]	=  'WinAnsiEncoding' ;

		switch ( strtolower ( $encoding_match [ 'encoding' ] ) )
		   {
		   	case 	'pdfdocencoding' 	:  $this -> Encoding	=  self::PDF_DOC_ENCODING 	; break ;
		   	case 	'macromanencoding' 	:  $this -> Encoding 	=  self::PDF_MAC_ROMAN_ENCODING ; break ;
		   	case 	'standardencoding' 	:  $this -> Encoding 	=  self::PDF_STANDARD_ENCODING 	; break ;
		   	case 	'winansiencoding' 	:
		   	default 		 	:  $this -> Encoding 	=  self::PDF_WIN_ANSI_ENCODING	;
		    }

		// Build a virgin character map using the detected encoding
		foreach  ( self::$Encodings  as  $code_array )
		   {
			$char 			=  $code_array [ $this -> Encoding ] ;
			$this -> Map [ $char ] 	=  $char ;
		    }

		// Extract the Differences array
	   	preg_match ( '/ \[ (?P<contents> [^\]]*)  \] /x', $definitions, $match ) ;
		$data 		=  trim ( preg_replace ( '/\s+(\d+)/', '/$1', $match [ 'contents' ] ) ) ;
		$items 		=  explode ( '/', $data ) ;

		// Some /Differences[] arrays start with a slash, some other not. If the first item is empty, then it started with a slash
		// so skip the item (it could also come from the fact that the starting number was preceded by spaces)
		if  ( ! $items [0] )
			$start	=  1 ;
		else
			$start	=  0 ;

		$index 		=  0 ;

		for  ( $i = $start, $item_count = count ( $items ) ; $i  <  $item_count ; $i ++ )
		   {
		   	$item 		=  trim ( $items [$i] ) ;

		   	// Integer value  : index of next character in map
			if  ( is_numeric ( $item ) )
				$index 	=  ( integer ) $item ;
			// String value : a character name, as defined by Adobe
			else
			   {
			   	// Keyword (character name) exists in the encoding table
				if  ( isset ( self::$Encodings [ $item ] ) )
				   {
					$this -> Map [ $index ] 	=  self::$Encodings [ $item ] [ $this -> Encoding ] ;
				    }
				// Not defined ; check if this is the "/gxx" notation, where "xx" is a number
				else if  ( preg_match ( '/g (?P<value> \d+)/x', $item, $match ) )
				   {
					$value		=  ( integer ) $match [ 'value' ] ;

					// In my current state of investigations, the /g notation has the following characteristics :
					// - The value 29 must be added to the number after the "/g" string (why ???)
					// - The value after the "/g" string can be greater than 255, meaning that it could be Unicode codepoint
					// This has to be carefully watched before revision
					$value	+=  29 ;

					$this -> Map [ $index ]		=  $value ;
				    }
				// Otherwise, put a quotation mark instead
				else
					$this -> Map [ $index ] 	=  ord ( '?' ) ;

				$index ++ ;
			    }
		    }
	    }


	/*--------------------------------------------------------------------------------------------------------------

	        Interface implementations.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function  count ( )
	   { return ( count ( $this -> Map ) ) ; }


	public function  offsetExists ( $offset )
	   { return ( isset ( $this -> Map [ $offset ] ) ) ; }


	public function  offsetGet ( $offset )
	   {
		if  ( isset ( $this -> Map [ $offset ] ) )
			$ord		=  $this -> Map [ $offset ] ;
		else
			$ord		=  $offset ;

		// Check for final character translations (concerns only a few number of characters)
		if  ( $this -> Encoding  ==  self::PDF_WIN_ANSI_ENCODING  &&  isset ( PdfTexterFont::$WinAnsiCharacterMap [ $ord ] ) )
			$ord	=  PdfTexterFont::$WinAnsiCharacterMap [ $ord ] ;
		else if  ( $this -> Encoding  ==  self::PDF_MAC_ROMAN_ENCODING  &&  isset ( PdfTexterFont::$MacRomanCharacterMap [ $ord ] ) )
			$ord	=  PdfTexterFont::$MacRomanCharacterMap [ $ord ] ;
		// As far as I have been able to see, the values expressed by the /Differences tag were the only ones used within the
		// Pdf document ; however, handle the case where some characters do not belong to the characters listed by /Differences,
		// and use the official Adobe encoding maps when necessary
		else if  ( isset ( self::$Encodings [ $ord ] [ $this -> Encoding ] ) )
			$ord	=  self::$Encodings [ $ord ] [ $this -> Encoding ] ;
		
		return ( $this -> CodePointToUtf8 ( $ord ) ) ;
	    }
    }


/*==============================================================================================================

    PdfTexterPageMap -
        A class for detecting page objects mappings and retrieving page number for a specified object.
	There is a quadruple level of indirection here :

	- The first level contains a /Type /Catalog parameter, with a /Pages one that references an object which
	  contains a /Count and /Kids. I don't know yet if the /Pages parameter can reference more than one
	  object using the array notation. However, the class is designed to handle such situations.
	- The object containing the /Kids parameter references objects who, in turn, lists the objects contained
	  into one single page.
	- Each object referenced in /Kids has a /Type/Page parameter, together with /Contents, which lists the
	  objects of the current page.

	Object references are of the form : "x y R", where "x" is the object number.

	Of course, anything can be in any order, otherwise it would not be funny ! Consider the following 
	example :

		(1) 5 0 obj
			<< ... /Pages 1 0 R ... >>
		    endobj

		(2) 1 0 obj
			<< ... /Count 1 /Kids[6 0 R] ... /Type/Pages ... >>
		    endobj

		(3)  6 0 obj
			<< ... /Type/Page ... /Parent 1 0 R ... /Contents [10 0 R 11 0 R ... x 0 R]
		     endobj

	Object #5 says that object #1 contains the list of page contents (in this example, there is only one page,
	referenced by object #6).
	Object #6 says that the objects #10, #11 through #x are contained into the same page.
	The quadruple indirection comes when you are handling one of the objects referenced in object #6 and you
	need to retrieve their page number...

	Of course, you cannot rely on the fact that all objects appear in logical order.

	And, of course #2, there may be no page catalog at all ! in such cases, objects containing drawing 
	instructions will have to be considered as a single page, whose number will be sequential.

	And, of course #3, as this is the case with the official PDF 1.7 Reference from Adobe, there can be a
	reference to a non-existing object which was meant to contain the /Kids parameter (!). In this case,
	taking the ordinal number of objects of type (3) gives the page number minus one.

	One mystery is that the PDF 1.7 Reference file contains 1310 pages but only 1309 are recognized here...

  ==============================================================================================================*/
class  PdfTexterPageMap		extends  PdfObjectBase
   {
	// Page contents are (normally) first described by a catalog
	// Although there should be only one entry for that, this property is defined as an array, as you need to really
	// become paranoid when handling pdf contents...
	protected	$PageCatalogs		=  [] ;
	// Entries that describe which page contains which text objects. Of course, these can be nested otherwise it would not be funny !
	protected	$PageKids		=  [] ;
	// Terminal entries : they directly give the ids of the objects belonging to a page
	protected	$PageContents		=  [] ;
	// Note that all the above arrays are indexed by object id and filled with the data collected by calling the Peek() Method...

	// Once the Peek() method has collected page contents & object information, the MapCatalog() method is called to create this array
	// which contains page numbers as keys, and the list of objects contained in this page as values
	public		$Pages			=  [] ;


	/*--------------------------------------------------------------------------------------------------------------
	
	    CONSTRUCTOR
		Creates a PdfTexterPageMap object. Actually, nothing significant is perfomed here, as this class' goal
		is to be used internally by PdfTexter.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  __construct ( )
	   {
		parent::__construct ( ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Peek - Peeks page information from a pdf object.
	
	    PROTOTYPE
	        $pagemap -> Peek ( ) ;
	
	    DESCRIPTION
	        Retrieves page information which can be of type (1), (2) or (3), as described in the class comments.
	
	    PARAMETERS
	        $object_id (integer) -
	                Id of the current pdf object.

		$object_data (string) -
			Pdf object contents.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Peek ( $object_id, $object_data )
	   {
		// Page catalog (/Type/Catalog and /Pages x 0 R)
		if  ( preg_match ( '#/Type \s* /Catalog#ix', $object_data )  &&  $this -> GetObjectReferences ( $object_id, $object_data, '/Pages', $references ) )
			$this -> PageCatalogs	=  array_merge ( $this -> PageCatalogs, $references ) ;
		// Object listing the object numbers that give the list of objects contained in a single page (/Types/Pages and /Count x /Kids[x1 0 R ... xn 0 R]
		else if  ( preg_match ( '#/Type \s* /Pages#ix', $object_data ) )
		   {
			if  ( $this -> GetObjectReferences ( $object_id, $object_data, '/Kids', $references ) )
			   {
				// Get kid count (knowing that sometimes, it is missing...)
				preg_match ( '#/Count \s+ (?P<count> \d+)#ix', $object_data, $match ) ;
				$page_count				=  ( isset ( $match [ 'count' ] ) ) ?  ( integer ) $match [ 'count' ] : false ;
				
				// Get parent object id
				preg_match ( '#/Parent \s+ (?P<parent> \d+)#ix', $object_data, $match ) ;
				$parent					=  ( isset ( $match [ 'parent' ] ) ) ?  ( integer ) $match [ 'parent' ] : false ;

				$this -> PageKids [ $object_id ]	=  
				   [
					'object'	=>  $object_id,
					'parent'	=>  $parent,
					'count'		=>  $page_count,
					'kids'		=>  $references 
				    ] ;
			    }
		    }
		// Object listing the other objects that are contained in this page (/Type/Page and /Contents[x1 0 R ... xn 0 R]
		else if  ( preg_match ( '#/Type \s* /Page\b#ix', $object_data ) )
		   {
			if  ( $this -> GetObjectReferences ( $object_id, $object_data, '/Contents', $references ) )
			   {
				preg_match ( '#/Parent \s+ (?P<parent> \d+)#ix', $object_data, $match ) ;
				$parent					=  ( isset ( $match [ 'parent' ] ) ) ?  (integer) $match [ 'parent' ] : false ;

				$this -> PageContents [ $object_id ]	=
				   [
					'object'	=>  $object_id,
					'parent'	=>  $parent,
					'contents'	=>  $references
				    ] ;
			    }
		    }
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        MapObjects - Builds a correspondance between object and page numbers.
	
	    PROTOTYPE
	        $pagemap -> MapObjects ( ) ;
	
	    DESCRIPTION
	        Builds a correspondance between object and page numbers. The page number corresponding to an object id 
		will after that be available using the array notation.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  MapObjects ( $objects )
	   {
		$kid_count	=  count ( $this -> PageKids ) ;

		// PDF files created short after the birth of Earth may have neither a page catalog nor page contents descriptions
		if  ( ! $this -> PageCatalogs  )
		   {
			// Later, during Pleistocen, references to page kids started to appear...
			if  ( $kid_count )
			   {
				foreach  ( array_keys ( $this -> PageKids )  as  $catalog )
					$this -> MapKeys ( $catalog, $current_page ) ;
			    }
			else
				$this -> Pages [1]	=  array_keys ( $objects ) ;
		    }
		// This is the ideal situation : there is a catalog that allows us to gather indirectly all page data
		else
		   {
			$current_page		=  1 ;

			foreach  ( $this -> PageCatalogs  as  $catalog )
			   {
				if  ( isset ( $this -> PageKids [ $catalog ] ) )
					$this -> MapKids ( $catalog, $current_page ) ;
			    }
		    }
	    }


	// MapKids -
	//	Tries to assign a page number to all page description objects that have been collected by the Peek() method.
	//	Also creates the Pages associative array, whose keys are page numbers and whose values are the ids of the objects
	//	that the page contains.
	protected function  MapKids ( $catalog, &$page ) 
	   {
		$entry		=  $this -> PageKids [ $catalog ] ;

		if  ( isset ( $this -> PageContents [ $entry [ 'kids' ] [0] ] ) )
		   {
			foreach  ( $entry [ 'kids' ]  as  $item )
			   {
				$this -> PageContents [ $item ]	[ 'page' ]	=  $page ;
				$this -> Pages [ $page ]			=  $this -> PageContents [ $item ] [ 'contents' ] ;
				$page ++ ;
			    }
		    }
		else
		   {
			foreach  ( $entry [ 'kids' ]  as  $kid )
				$this -> MapKids ( $kid, $page ) ;
		    }
	    }
    }
    

/*==============================================================================================================

    class PdfImage -
        Holds image data coming from pdf.

  ==============================================================================================================*/
abstract class  PdfImage			extends  PdfObjectBase 
   {
	// Image resource that can be used to process image data, using the php imagexxx() functions
	public		$ImageResource ;
	// Original image data
	protected	$ImageData ;


	/*--------------------------------------------------------------------------------------------------------------
	
	    CONSTRUCTOR
	        Creates a PdfImage object with a resource that can be used with imagexxx() php functions.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  __construct ( $image_data )
	   {
		$this -> ImageData	=  $image_data ;
		$this -> ImageResource	=  $this -> CreateImageResource ( $image_data ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        CreateImageResource - creates an image resource from the supplied image data.
	
	    PROTOTYPE
	        $resource	=  $this -> CreateImageResource ( $data ) ;
	
	    DESCRIPTION
	        Creates an image resource from the supplied image data.
		Whatever the input format, the internal format will be the one used by the gd library.
	
	    PARAMETERS
	        $data (string) -
	                Image data.

	 *-------------------------------------------------------------------------------------------------------------*/
	abstract protected function  CreateImageResource ( $image_data ) ;


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        SaveAs - Saves the current image to a file.
	
	    PROTOTYPE
	        $pdfimage -> SaveAs ( $output_file, $image_type = IMG_JPEG ) ;
	
	    DESCRIPTION
	        Saves the current image resource to the specified output file, in the specified format.
	
	    PARAMETERS
	        $output_file (string) -
	                Output filename.

		$image_type (integer) -
			Output format. Can be any of the predefined php constants IMG_*.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  SaveAs ( $output_file, $image_type = IMG_JPEG )
	   {
		$image_types		=  imagetypes ( ) ;

		switch  ( $image_type )
		   {
			case	IMG_JPEG :
			case	IMG_JPG :
				if  ( ! ( $image_types & IMG_JPEG )  &&  ! ( $image_types & IMG_JPG ) )
					error ( new PdfToTextException ( "Your current PHP version does not support JPG images." ) ) ;

				imagejpeg ( $this -> ImageResource, $output_file, 100 ) ;
				break ;

			case	IMG_GIF :
				if  ( ! ( $image_types & IMG_GIF ) )
					error ( new PdfToTextException ( "Your current PHP version does not support GIF images." ) ) ;

				imagegif ( $this -> ImageResource, $output_file ) ;
				break ;

			case	IMG_PNG :
				if  ( ! ( $image_types & IMG_PNG ) )
					error ( new PdfToTextException ( "Your current PHP version does not support PNG images." ) ) ;

				imagepng ( $this -> ImageResource, $output_file, 0 ) ;
				break ;
				
			case	IMG_WBMP :
				if  ( ! ( $image_types & IMG_WBMP ) )
					error ( new PdfToTextException ( "Your current PHP version does not support WBMP images." ) ) ;

				imagewbmp ( $this -> ImageResource, $output_file ) ;
				break ;
				
			case	IMG_XPM :
				if  ( ! ( $image_types & IMG_XPM ) )
					error ( new PdfToTextException ( "Your current PHP version does not support XPM images." ) ) ;

				imagexbm ( $this -> ImageResource, $output_file ) ;
				break ;

			default :
				error ( new PdfToTextException ( "Unknown image type #$image_type." ) ) ;
		    }
	    }
    }



/*==============================================================================================================

    class PdfJpegImage -
        Handles encoded JPG images.

  ==============================================================================================================*/
class  PdfJpegImage		extends  PdfImage 
   {
	public function  __construct ( $image_data )
	   {
		parent::__construct ( $image_data ) ;
	    }


	public function  CreateImageResource ( $image_data )
	   {
		return ( imagecreatefromstring ( $image_data ) ) ;
	    }
    }


/*==============================================================================================================

    class PdfFaxImage -
        Handles encoded CCITT Fax images.

  ==============================================================================================================*/
class  PdfFaxImage		extends  PdfImage 
   {
	public function  __construct ( $image_data )
	   {
		parent::__construct ( $image_data ) ;
	    }


	public function  CreateImageResource ( $image_data )
	   {
		error ( new PdfToTextException ( "Decoding of CCITT Fax image format is not yet implemented." ) ) ;
		//return ( imagecreatefromstring ( $image_data ) ) ;
	    }
    }
?>