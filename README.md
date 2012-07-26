# The Color of the Internet #

[a neen]
Programmatically target an average color of the Internet.

## Authors ##

Sean Harvey
Sam Templeman

## Abstract ##

This project was conceived in the context of the Rapid Web course at The Art Institute of Portland in Portland, Oregon. The objective is to derive a single hexadecimal color value that is as close an average as possible of all of the color values on the entire Internet. This value will be obtained with a specialized web spider script designed to scrape websites for color values, store them, and ultimately average them together to produce a final representative "Color of the Internet".

### Flow ###

1. Generate and resolve valid root URL. This becomes the target URL.
2. Parse target URL for all associated .css files.
3. Resolve absolute paths to the .css files. Store paths in DB.
4. Referencing DB paths, download and parse .css files for color values.
5. Split each color value into RGB values. Store RGB values in DB.
6. Referencing DB color values, average the values for each color type (R's, G's, and B's).
7. Convert final averaged RGB value to hexadecimal value.
8. Update front-end to display this new color.
9. Repeat [automate with cron].

Ultimately, this process should be fully automated, so instead of a static average, the Color of the Internet will actually be a rolling average. The DB will persistently accumulate more and more color values. Theoretically, since we cannot realistically calculate the average of every color value on the Web, the accuracy of our final average will be dependent on our sample size. As the size of the sample grows, our average color will grow more accurate.
