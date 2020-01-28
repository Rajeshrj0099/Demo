# Automated symbolic link removal for the CATS ASP website module
# This script should be remove all ASP specific sym-links.

echo -en "Removing the ASP website \"blog\" symbolic link... "
rm -f "~/catsRC/cats/blog" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"catsnewversion.php\" symbolic link... "
rm -f "~/catsRC/cats/catsnewversion.php" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"cpl\" symbolic link... "
rm -f "~/catsRC/cats/cpl" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"clientSpecific\" symbolic link... "
rm -f "~/catsRC/cats/clientSpecific" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"cicla\" symbolic link... "
rm -f "~/catsRC/cats/cicla" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"campaigns\" symbolic link... "
rm -f "~/catsRC/cats/campaigns" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"bugs\" symbolic link... "
rm -f "~/catsRC/cats/bugs" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"bin\" symbolic link... "
rm -f "~/catsRC/cats/bin" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"forum\" symbolic link... "
rm -f "~/catsRC/cats/forum" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"indexingTools\" symbolic link... "
rm -f "~/catsRC/cats/indexingTools" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"kb_upload\" symbolic link... "
rm -f "~/catsRC/cats/kb_upload" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"kb\" symbolic link... "
rm -f "~/catsRC/cats/kb" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"lists\" symbolic link... "
rm -f "~/catsRC/cats/lists" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"mint\" symbolic link... "
rm -f "~/catsRC/cats/mint" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"tracker\" symbolic link... "
rm -f "~/catsRC/cats/tracker" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"tarballs\" symbolic link... "
rm -f "~/catsRC/cats/tarballs" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"style.css\" symbolic link... "
rm -f "~/catsRC/cats/style.css" 2>/dev/null && echo "Success" || echo "Not Found!"
echo -en "Removing the ASP website \"professional\" symbolic link... "
rm -f "~/catsRC/cats/professional" 2>/dev/null && echo "Success" || echo "Not Found!"
