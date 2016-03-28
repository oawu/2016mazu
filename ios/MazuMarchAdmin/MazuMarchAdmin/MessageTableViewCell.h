//
//  MessageTableViewCell.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIImageView+WebCache.h"
#import "Message.h"


@interface MessageTableViewCell : UITableViewCell

@property UIView *border;
@property UILabel *content;
@property UILabel *ip;
@property UILabel *time;
@property UIImageView *avatar;
@property Message *message;
- (MessageTableViewCell *) initCellWithStyle:(Message *)message style:(UITableViewCellStyle)style reuseIdentifier:(nullable NSString *)reuseIdentifier;

@end
