# NP_MostCommented-2.0
An updated version of the Most Commented plugin that takes into account such factors as item age and comment count.

The original Most Commented simply counted all the comments and gave you the items with the highest comment count. This version is a touch more complex. It calcualtes not only the number of comments but also factors in the age of the item and the age of each comment. It favours items with more comments as before but also gives preference to items that have more recent comments as well as newer items.

The maths goes something like this: the square root of one over the age in days over 365 squared; times the comment count squared; times the sum of suare root of comment itema ge in days over 365 squared.

For a more detailed breakdown of how the maths works to ballance age vs comment count you might like to visit: http://lordmatt.co.uk/item/2641

If you use the original Most Commented you can upgrade by simply replacing your current plugin file with this one.

100% compatable with NucleusCMS, LMNucleusCMS and NucleusCMS::MOON
